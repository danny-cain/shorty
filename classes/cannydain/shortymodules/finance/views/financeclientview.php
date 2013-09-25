<?php

namespace CannyDain\ShortyModules\Finance\Views;

use CannyDain\Shorty\Views\ShortyView;

class FinanceClientView extends ShortyView
{
    protected $_apiClientURI = '';

    public function display()
    {
        echo '<div id="accountsContainer">';
            echo '<div class="transferView">';
                echo '<div class="sourceContainer">';
                    echo '<div style="font-weight: bold;">From</div>';
                    echo '<input type="text" class="sourceAccount" readonly="readonly" />';
                echo '</div>';

                echo '<div class="destinationContainer">';
                    echo '<div style="font-weight: bold;">To</div>';
                    echo '<input type="text" class="destinationAccount" readonly="readonly" />';
                echo '</div>';

                echo '<div  class="detailContainer">';
                    echo '<div style="font-weight: bold;">Detail</div>';
                    echo '<input type="text" class="detail" />';
                echo '</div>';

                echo '<div  class="amountContainer">';
                    echo '<div style="font-weight: bold;">Amount</div>';
                    echo '&pound;<input type="text" class="amount" />';
                echo '</div>';

                echo '<div  class="buttonContainer">';
                    echo '<div>&nbsp;</div>';
                    echo '<button class="transferButton">Transfer</button>';
                echo '</div>';
            echo '</div>';
        echo '</div>';

        echo '<div id="accountDetailContainer">';

        echo '</div>';

        echo <<<HTML
<script type="text/javascript" src="{$this->_apiClientURI}"></script>
<script type="text/javascript">
    function AccountDetailView(client)
    {
        this.client = client;
        this.element = $('#accountDetailContainer');

        var view = this;

        this.viewAccount = function(id, from, to)
        {
            if (from == undefined)
                from = "last month";

            if (to == undefined)
                to = "today";

            this.client.getAccountDetails(id, from, to, function(account, balance, transactions, from, to)
            {
                view.displayAccount(from, to, account, balance, transactions, from, to);
            });
        };

        this.createTransactionRow = function(date, detail, amount, type)
        {
            var ret = $('<div></div>');

            var dateCell = $('<div></div>');
            var detailCell = $('<div></div>');
            var amountCell = $('<div></div>');

            dateCell.css('display', 'inline-block').css('width', '20%').css('vertical-align', 'top');
            detailCell.css('display', 'inline-block').css('width', '20%').css('vertical-align', 'top');
            amountCell.css('display', 'inline-block').css('width', '20%').css('vertical-align', 'top');

            amount = amount / 100;
            var amountText = '&pound;' + amount;

            if (type == 'c')
                amountText = '-' + amountText;

            dateCell.text(date);
            detailCell.text(detail);
            amountCell.html(amountText);

            ret.append(dateCell);
            ret.append(detailCell);
            ret.append(amountCell);

            return ret;
        };

        this.displayAccount = function(startDate, endDate, account, balance, transactions, from, to)
        {
            this.element.empty();

            var title = account.name + " - " + from + " to " + to;
            this.element.append('<h1>' + title + '</h1>');

            if (balance < 0)
                this.element.append(this.createTransactionRow(startDate, "opening balance", balance * -1, 'c'));
            else if (balance > 0)
                this.element.append(this.createTransactionRow(startDate, "opening balance", balance, 'd'));

            for (var i = 0; i < transactions.length; i ++)
            {
                if (transactions[i].type != 't')
                    continue;

                var date = transactions[i].date;
                var amount = transactions[i].amount;
                var detail = transactions[i].description;
                var type = transactions[i].source == account.id ? 'c' : 'd';

                this.element.append(this.createTransactionRow(date, detail, amount, type));
            }
        };
    }

    function AccountView(client, accountInfo, transferView, detailView)
    {
        this.account = accountInfo;
        this.client = client;
        this.element = $('<div class="account"></div>');
        this.detailView = detailView;

        this.element.append($('<strong>' + this.account.name + '</strong>'));
        this.element.append($('<div class="balance"></div>'));

        var view = this;

        this.element.on('click', function(e)
        {
            view.detailView.viewAccount(view.account.id, "-1 month", "today");
        });

        this.element.on('contextmenu', function(e)
        {
            window.contextMenu.drawMenu(e.pageX, e.pageY,
            [
                new MenuInfo("Transfer From", function()
                {
                    if (transferView != undefined)
                        transferView.transferFrom(view.account.id, view.account.name);
                }),
                new MenuInfo("Transfer To", function()
                {
                    if (transferView != undefined)
                        transferView.transferTo(view.account.id, view.account.name);
                }),
                new MenuInfo("Balance Account", function()
                {
                    client.balanceAccount(view.account.id, function()
                    {
                        view.updateBalance();
                    });
                })
            ]);

            e.preventDefault();
            e.stopPropagation();
        });

        this.updateBalance = function()
        {
            this.client.getBalance(this.account.id, "", function(balance)
            {
                balance = balance / 100;

                $('.balance', view.element).html('&pound;' + balance);

                view.client.getLastBalanceDate(view.account.id, function(date)
                {
                    view.element.attr("title", "Last Balanced " + date);
                });
            });
        };

        this.updateBalance();
    }

    function TransferView(client, accountsView)
    {
        this.client = client;
        this.accountsView = accountsView;

        this.element = $('.transferView');

        this.fromAccountLookup = $('.sourceAccount', this.element);
        this.toAccountLookup = $('.destinationAccount', this.element);
        this.detailInput = $('.detail', this.element);
        this.amountInput = $('.amount', this.element);
        this.transferButton = $('.transferButton', this.element);

        var view = this;

        this.transferFrom = function(id, name)
        {
            this.fromAccountLookup.attr('data-id', id);
            this.fromAccountLookup.val(name);
        };

        this.transferTo = function(id, name)
        {
            this.toAccountLookup.attr('data-id', id);
            this.toAccountLookup.val(name);
        };

        this.transferButton.on('click', function()
        {
            var source = parseInt(view.fromAccountLookup.attr('data-id'));
            var dest = parseInt(view.toAccountLookup.attr('data-id'));
            var detail = view.detailInput.val();
            var amount = parseFloat(view.amountInput.val());

            var messages = [];

            if (isNaN(source))
                messages.push("Must specify a source account");

            if (isNaN(dest))
                messages.push("Must specify a destination account");

            if (isNaN(amount))
                messages.push("Must specify an amount");

            if (detail == undefined || detail == null || detail == '')
                messages.push("Must specify details of the transaction");

            if (messages.length > 0)
            {
                alert("Unable to transfer:\\r\\n" + messages.join("\\r\\n"));
                return;
            }

            amount = amount * 100;

            client.transfer(source, dest, amount, detail, function()
            {
                view.fromAccountLookup.attr('data-id', '');
                view.toAccountLookup.attr('data-id', '');
                view.fromAccountLookup.val('');
                view.toAccountLookup.val('');
                view.detailInput.val('');
                view.amountInput.val('0');

                view.accountsView.update();
            });
        });
    }

    function AccountsView(client, detailView)
    {
        this.client = client;
        this.transferView = null;
        this.detailView = detailView;

        var view = this;

        this.update = function()
        {
            $('.account', $('#accountsContainer')).remove();

            this.client.getAllAccounts(function(accounts)
            {
                for (var i = 0; i < accounts.length; i ++)
                {
                    var acct = new AccountView(view.client, accounts[i], view.transferView, view.detailView);
                    $('#accountsContainer').append(acct.element);
                }
            });
        };
    }

    $(document).ready(function()
    {
        var finance = new window.shorty.apiClients.Finance();
        var detailView = new AccountDetailView(finance);
        var accountsView = new AccountsView(finance, detailView);
        var transferView = new TransferView(finance, accountsView);

        accountsView.transferView = transferView;
        //$('#accountsContainer').append(transferView.element);

        accountsView.update();
    });
</script>
HTML;
    }

    public function setApiClientURI($apiClientURI)
    {
        $this->_apiClientURI = $apiClientURI;
    }

    public function getApiClientURI()
    {
        return $this->_apiClientURI;
    }
}
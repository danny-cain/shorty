<?php

namespace CannyDain\Shorty\Config;

class ShortyConfiguration extends Configuration
{
    const KEY_DATABASE_USER = 'shorty.database.user';
    const KEY_DATABASE_PASS = 'shorty.database.pass';
    const KEY_DATABASE_HOST = 'shorty.database.host';
    const KEY_DATABASE_NAME = 'shorty.database.name';

    const KEY_SMTP_HOST = 'shorty.emailing.smtp.host';
    const KEY_SMTP_USER = 'shorty.emailing.smtp.user';
    const KEY_SMTP_PASS = 'shorty.emailing.smtp.pass';

    const KEY_FILE_SYSTEM_ROOT = 'shorty.filesystem.root';
    const KEY_PRIVATE_DATA_ROOT = 'shorty.filesystem.privateRoot';
    const KEY_STYLE_ROOT = 'shorty.filesystem.styleRoot';
    const KEY_SCRIPT_ROOT = 'shorty.filesystem.scriptRoot';
    const KEY_PUBLIC_ROOT = 'shorty.filesystem.publicRoot';
}
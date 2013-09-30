<?php

namespace CannyDain\ShortyModules\CVLibrary\Views;

use CannyDain\Lib\SimplePDFWriter\Helpers\PDFTableHelper;
use CannyDain\Lib\SimplePDFWriter\PDFWriter;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\UserConsumer;
use CannyDain\Shorty\Helpers\UserHelper;
use CannyDain\ShortyModules\CVLibrary\Models\CV;
use CannyDain\ShortyModules\CVLibrary\Models\Experience;
use CannyDain\ShortyModules\CVLibrary\Models\Qualification;

class PDFView implements ViewInterface, UserConsumer
{
    /**
     * @var CV
     */
    protected $_cv;

    /**
     * @var Qualification[]
     */
    protected $_qualifications;

    /**
     * @var Experience[]
     */
    protected $_experience;

    /**
     * @var UserHelper
     */
    protected $_users;

    /**
     * @var PDFWriter
     */
    protected $_writer;

    /**
     * @var PDFTableHelper
     */
    protected $_tableHelper;

    public function __construct()
    {
        $this->_writer = new PDFWriter();
        $this->_tableHelper = new PDFTableHelper();
        $this->_tableHelper->setWriter($this->_writer);
        $this->_writer->AddPage();
    }

    public function display()
    {
        header("Content-Disposition: inline; filename=\"cv.pdf\"");

        $this->_writeCV();

        echo $this->_writer->Output('cv.pdf', 'S');
    }

    protected function _writeCV()
    {
        $this->_writer->Bold(true);
        $this->_writer->Underline(true);
        $this->_writer->SetFontSize(24);

            $this->_writer->centredText($this->_cv->getPageTitle());

        $this->_writer->Bold(false);
        $this->_writer->Underline(false);
        $this->_writer->SetFontSize(12);

        $this->_writeAboutMe();
        $this->_writeQualifications();
        $this->_writeExperience();
        $this->_writeHobbiesAndInterests();
    }

    protected function _writeHobbiesAndInterests()
    {
        $this->_writeSectionTitle('Hobbies and Interests');
        $this->_writer->writeText($this->_cv->getHobbiesAndInterests());
    }

    protected function _writeAboutMe()
    {
        $this->_writeSectionTitle('About Me');
        $this->_writer->writeText($this->_cv->getAboutMe());
    }

    protected function _writeSectionTitle($title)
    {
        $this->_writer->beginBlockElement(2);
        $this->_writer->Bold(true);
        $this->_writer->Underline(true);
        $this->_writer->SetFontSize(16);
        $this->_writer->writeText($title);
        $this->_writer->SetFont('', '', 12);
        $this->_writer->lineBreak(true);
    }

    protected function _writeQualifications()
    {
        $headers = array
        (
            'Course',
            'Grade',
            'Level',
            'Year',
        );

        $widths = array
        (
            40,40,40,40
        );

        $rowData = array();

        $this->_writeSectionTitle('Qualifications');

        foreach ($this->_qualifications as $qual)
        {
            $row = array($qual->getCourse(), $qual->getGrade(),$qual->getLevel(), $qual->getYear());
            $rowData[] = $row;
        }

        $this->_tableHelper->table($headers, $widths, $rowData);
        $this->_writer->endBlockElement();
    }

    protected function _writeExperience()
    {
        $headers = array
        (
            'Title',
            'Company',
            'From',
            'To',
            'Description',
        );

        $widths = array
        (
            35,35,35,35,35
        );

        $rowData = array();

        $this->_writeSectionTitle('Experience');

        foreach ($this->_experience as $exp)
        {
            $endDate = 'current';
            if ($exp->getEmploymentEnd() > $exp->getEmploymentStart())
                $endDate = date('Y-m', $exp->getEmploymentEnd());

            $row = array
            (
                $exp->getJobTitle(),
                $exp->getCompany(),
                date('Y-m', $exp->getEmploymentStart()),
                $endDate,
                $exp->getDescription(),
            );
            $rowData[] = $row;
        }

        $this->_tableHelper->table($headers, $widths, $rowData);
        $this->_writer->endBlockElement();
    }

    public function getContentType()
    {
        //return 'text/html';
        //return 'text/plain';
        return 'application/pdf';
    }

    /**
     * @param \CannyDain\ShortyModules\CVLibrary\Models\CV $cv
     */
    public function setCv($cv)
    {
        $this->_cv = $cv;
    }

    /**
     * @return \CannyDain\ShortyModules\CVLibrary\Models\CV
     */
    public function getCv()
    {
        return $this->_cv;
    }

    public function setExperience($experience)
    {
        $this->_experience = $experience;
    }

    public function getExperience()
    {
        return $this->_experience;
    }

    public function setQualifications($qualifications)
    {
        $this->_qualifications = $qualifications;
    }

    public function getQualifications()
    {
        return $this->_qualifications;
    }

    public function consumerUserHelper(UserHelper $helper)
    {
        $this->_users = $helper;
    }
}
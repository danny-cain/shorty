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
use ThirdParty\FPDF\MakePDFFont;

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

    protected $_fontFiles = array();
    protected $_defaultFont = 'josefinsans';

    public function __construct()
    {
        $this->_writer = new PDFWriter();
        $this->_tableHelper = new PDFTableHelper();
        $this->_tableHelper->setWriter($this->_writer);
        $this->_writer->AddPage();

        $this->_fontFiles['josefinsans'] = array
        (
            'regular' => dirname((dirname(__FILE__))).'/fonts/JosefinSans-Regular.ttf',
            'italic' => dirname((dirname(__FILE__))).'/fonts/JosefinSans-Italic.ttf',
            'bold' => dirname((dirname(__FILE__))).'/fonts/JosefinSans-Bold.ttf',
            'bold-italic' => dirname((dirname(__FILE__))).'/fonts/JosefinSans-BoldItalic.ttf',
        );

        $fontMaker = new MakePDFFont();
        foreach ($this->_fontFiles as $name => $files)
        {
            $files = $fontMaker->createFontFileSet($name, $files['regular'], $files['bold'], $files['italic'], $files['bold-italic']);
            $this->_writer->AddFont($name, '', $files['regular']);
            $this->_writer->AddFont($name, 'B', $files['bold']);
            $this->_writer->AddFont($name, 'I', $files['italic']);
            $this->_writer->AddFont($name, 'BI', $files['bold-italic']);
        }

        $this->_writer->SetFont($this->_defaultFont, '', 12);
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

        $this->_writePersonalDetails();
        $this->_writeAboutMe();
        $this->_writeQualifications();
        $this->_writeExperience();
        $this->_writeHobbiesAndInterests();
    }

    protected function _writePersonalDetails()
    {
        $this->_writeSectionTitle('Personal Details');

        $this->_writer->beginBlockElement();
            $this->_writer->Bold();
            $this->_writer->Write(5, 'Name: ');
            $this->_writer->Bold(false);
            $this->_writer->write(5, $this->_cv->getFullName());
        $this->_writer->endBlockElement();

        $this->_writer->beginBlockElement();
            $this->_writer->Bold();
            $this->_writer->Write(5, 'Contact Number: ');
            $this->_writer->Bold(false);
            $this->_writer->write(5, $this->_cv->getContactNumber());
        $this->_writer->endBlockElement();

        $this->_writer->beginBlockElement();
            $this->_writer->Bold();
            $this->_writer->Write(5, 'Address: ');
            $this->_writer->Bold(false);
        $this->_writer->endBlockElement();

        $this->_writer->beginBlockElement();
            $lines = explode("\n", $this->_cv->getAddress());
            foreach ($lines as $line)
            {
                $this->_writer->writeText("\t\t\t\t\t\t\t\t\t\t\t\t\t\t".$line."\n");
            }
            //$this->_writer->write(5, $address);
        $this->_writer->endBlockElement();
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
        $this->_writer->beginBlockElement(4);
        $this->_writer->Bold(true);
        $this->_writer->Underline(true);
        $this->_writer->SetFontSize(16);
        $this->_writer->writeText($title);
        $this->_writer->SetFont('', '', 12);
        $this->_writer->lineBreak(true);
        $this->_writer->lineBreak(true);
    }

    protected function _writeQualifications()
    {
        if (count($this->_qualifications) == 0)
            return;

        $headers = array
        (
            'Course',
            'Grade',
            'Level',
            'Year',
        );

        $widths = array
        (
            '25%', '25%', '25%', '25%'
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
        if (count($this->_experience) == 0)
            return;

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
            '20%', '20%', '20%', '20%', '20%'
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
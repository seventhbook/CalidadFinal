<?php

namespace PhpOffice\PhpSpreadsheet\Writer;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriterException;

abstract class Pdf extends Html
{
    /**
     * Temporary storage directory.
     *
     * @var string
     */
    protected $tempDir = '';

    /**
     * Font.
     *
     * @var string
     */
    protected $font = 'freesans';

    /**
     * Orientation (Over-ride).
     *
     * @var string
     */
    protected $orientation;

    /**
     * Paper size (Over-ride).
     *
     * @var int
     */
    protected $paperSize;

    /**
     * Temporary storage for Save Array Return type.
     *
     * @var string
     */
    private $saveArrayReturnType;

    /**
     * Paper Sizes xRef List.
     *
     * @var array
     */
    protected static $paperSizes = [
        PageSetup::PAPERSIZE_LETTER => 'LETTER', 
        PageSetup::PAPERSIZE_LETTER_SMALL => 'LETTER', 
        PageSetup::PAPERSIZE_TABLOID => [792.00, 1224.00], 
        PageSetup::PAPERSIZE_LEDGER => [1224.00, 792.00], 
        PageSetup::PAPERSIZE_LEGAL => 'LEGAL', 
        PageSetup::PAPERSIZE_STATEMENT => [396.00, 612.00], 
        PageSetup::PAPERSIZE_EXECUTIVE => 'EXECUTIVE', 
        PageSetup::PAPERSIZE_A3 => 'A3', 
        PageSetup::PAPERSIZE_A4 => 'A4', 
        PageSetup::PAPERSIZE_A4_SMALL => 'A4', 
        PageSetup::PAPERSIZE_A5 => 'A5', 
        PageSetup::PAPERSIZE_B4 => 'B4', 
        PageSetup::PAPERSIZE_B5 => 'B5', 
        PageSetup::PAPERSIZE_FOLIO => 'FOLIO', 
        PageSetup::PAPERSIZE_QUARTO => [609.45, 779.53], 
        PageSetup::PAPERSIZE_STANDARD_1 => [720.00, 1008.00], 
        PageSetup::PAPERSIZE_STANDARD_2 => [792.00, 1224.00], 
        PageSetup::PAPERSIZE_NOTE => 'LETTER', 
        PageSetup::PAPERSIZE_NO9_ENVELOPE => [279.00, 639.00], 
        PageSetup::PAPERSIZE_NO10_ENVELOPE => [297.00, 684.00], 
        PageSetup::PAPERSIZE_NO11_ENVELOPE => [324.00, 747.00], 
        PageSetup::PAPERSIZE_NO12_ENVELOPE => [342.00, 792.00], 
        PageSetup::PAPERSIZE_NO14_ENVELOPE => [360.00, 828.00], 
        PageSetup::PAPERSIZE_C => [1224.00, 1584.00], 
        PageSetup::PAPERSIZE_D => [1584.00, 2448.00], 
        PageSetup::PAPERSIZE_E => [2448.00, 3168.00], 
        PageSetup::PAPERSIZE_DL_ENVELOPE => [311.81, 623.62], 
        PageSetup::PAPERSIZE_C5_ENVELOPE => 'C5', 
        PageSetup::PAPERSIZE_C3_ENVELOPE => 'C3', 
        PageSetup::PAPERSIZE_C4_ENVELOPE => 'C4', 
        PageSetup::PAPERSIZE_C6_ENVELOPE => 'C6', 
        PageSetup::PAPERSIZE_C65_ENVELOPE => [323.15, 649.13], 
        PageSetup::PAPERSIZE_B4_ENVELOPE => 'B4', 
        PageSetup::PAPERSIZE_B5_ENVELOPE => 'B5', 
        PageSetup::PAPERSIZE_B6_ENVELOPE => [498.90, 354.33], 
        PageSetup::PAPERSIZE_ITALY_ENVELOPE => [311.81, 651.97], 
        PageSetup::PAPERSIZE_MONARCH_ENVELOPE => [279.00, 540.00], 
        PageSetup::PAPERSIZE_6_3_4_ENVELOPE => [261.00, 468.00], 
        PageSetup::PAPERSIZE_US_STANDARD_FANFOLD => [1071.00, 792.00], 
        PageSetup::PAPERSIZE_GERMAN_STANDARD_FANFOLD => [612.00, 864.00], 
        PageSetup::PAPERSIZE_GERMAN_LEGAL_FANFOLD => 'FOLIO', 
        PageSetup::PAPERSIZE_ISO_B4 => 'B4', 
        PageSetup::PAPERSIZE_JAPANESE_DOUBLE_POSTCARD => [566.93, 419.53], 
        PageSetup::PAPERSIZE_STANDARD_PAPER_1 => [648.00, 792.00], 
        PageSetup::PAPERSIZE_STANDARD_PAPER_2 => [720.00, 792.00], 
        PageSetup::PAPERSIZE_STANDARD_PAPER_3 => [1080.00, 792.00], 
        PageSetup::PAPERSIZE_INVITE_ENVELOPE => [623.62, 623.62], 
        PageSetup::PAPERSIZE_LETTER_EXTRA_PAPER => [667.80, 864.00], 
        PageSetup::PAPERSIZE_LEGAL_EXTRA_PAPER => [667.80, 1080.00], 
        PageSetup::PAPERSIZE_TABLOID_EXTRA_PAPER => [841.68, 1296.00], 
        PageSetup::PAPERSIZE_A4_EXTRA_PAPER => [668.98, 912.76], 
        PageSetup::PAPERSIZE_LETTER_TRANSVERSE_PAPER => [595.80, 792.00], 
        PageSetup::PAPERSIZE_A4_TRANSVERSE_PAPER => 'A4', 
        PageSetup::PAPERSIZE_LETTER_EXTRA_TRANSVERSE_PAPER => [667.80, 864.00], 
        PageSetup::PAPERSIZE_SUPERA_SUPERA_A4_PAPER => [643.46, 1009.13], 
        PageSetup::PAPERSIZE_SUPERB_SUPERB_A3_PAPER => [864.57, 1380.47], 
        PageSetup::PAPERSIZE_LETTER_PLUS_PAPER => [612.00, 913.68], 
        PageSetup::PAPERSIZE_A4_PLUS_PAPER => [595.28, 935.43], 
        PageSetup::PAPERSIZE_A5_TRANSVERSE_PAPER => 'A5', 
        PageSetup::PAPERSIZE_JIS_B5_TRANSVERSE_PAPER => [515.91, 728.50], 
        PageSetup::PAPERSIZE_A3_EXTRA_PAPER => [912.76, 1261.42], 
        PageSetup::PAPERSIZE_A5_EXTRA_PAPER => [493.23, 666.14], 
        PageSetup::PAPERSIZE_ISO_B5_EXTRA_PAPER => [569.76, 782.36], 
        PageSetup::PAPERSIZE_A2_PAPER => 'A2', 
        PageSetup::PAPERSIZE_A3_TRANSVERSE_PAPER => 'A3', 
        PageSetup::PAPERSIZE_A3_EXTRA_TRANSVERSE_PAPER => [912.76, 1261.42], 
    ];

    /**
     * Create a new PDF Writer instance.
     *
     * @param Spreadsheet $spreadsheet Spreadsheet object
     */
    public function __construct(Spreadsheet $spreadsheet)
    {
        parent::__construct($spreadsheet);
        $this->setUseInlineCss(true);
        $this->tempDir = File::sysGetTempDir();
    }

    /**
     * Get Font.
     *
     * @return string
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * Set font. Examples:
     *      'arialunicid0-chinese-simplified'
     *      'arialunicid0-chinese-traditional'
     *      'arialunicid0-korean'
     *      'arialunicid0-japanese'.
     *
     * @param string $fontName
     *
     * @return Pdf
     */
    public function setFont($fontName)
    {
        $this->font = $fontName;

        return $this;
    }

    /**
     * Get Paper Size.
     *
     * @return int
     */
    public function getPaperSize()
    {
        return $this->paperSize;
    }

    /**
     * Set Paper Size.
     *
     * @param string $pValue Paper size see PageSetup::PAPERSIZE_*
     *
     * @return self
     */
    public function setPaperSize($pValue)
    {
        $this->paperSize = $pValue;

        return $this;
    }

    /**
     * Get Orientation.
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set Orientation.
     *
     * @param string $pValue Page orientation see PageSetup::ORIENTATION_*
     *
     * @return self
     */
    public function setOrientation($pValue)
    {
        $this->orientation = $pValue;

        return $this;
    }

    /**
     * Get temporary storage directory.
     *
     * @return string
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }

    /**
     * Set temporary storage directory.
     *
     * @param string $pValue Temporary storage directory
     *
     * @throws WriterException when directory does not exist
     *
     * @return self
     */
    public function setTempDir($pValue)
    {
        if (is_dir($pValue)) {
            $this->tempDir = $pValue;
        } else {
            throw new WriterException("Directory does not exist: $pValue");
        }

        return $this;
    }

    /**
     * Save Spreadsheet to PDF file, pre-save.
     *
     * @param string $pFilename Name of the file to save as
     *
     * @throws WriterException
     *
     * @return resource
     */
    protected function prepareForSave($pFilename)
    {
        //  garbage collect
        $this->spreadsheet->garbageCollect();

        $this->saveArrayReturnType = Calculation::getArrayReturnType();
        Calculation::setArrayReturnType(Calculation::RETURN_ARRAY_AS_VALUE);

        //  Open file
        $fileHandle = fopen($pFilename, 'w');
        if ($fileHandle === false) {
            throw new WriterException("Could not open file $pFilename for writing.");
        }

        //  Set PDF
        $this->isPdf = true;
        //  Build CSS
        $this->buildCSS(true);

        return $fileHandle;
    }

    /**
     * Save PhpSpreadsheet to PDF file, post-save.
     *
     * @param resource $fileHandle
     */
    protected function restoreStateAfterSave($fileHandle)
    {
        //  Close file
        fclose($fileHandle);

        Calculation::setArrayReturnType($this->saveArrayReturnType);
    }
}

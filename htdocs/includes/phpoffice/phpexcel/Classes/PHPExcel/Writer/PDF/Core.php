<?php
/**
 *  PHPExcel
 *
 *  Copyright (c) 2006 - 2014 PHPExcel
 *
 *  This library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 *
 *  This library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with this library; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *  @category    PHPExcel
 *  @package     PHPExcel_Writer_PDF
 *  @copyright   Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 *  @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 *  @version     ##VERSION##, ##DATE##
 */


/**
 *  PHPExcel_Writer_PDF_Core
 *
 *  @category    PHPExcel
 *  @package     PHPExcel_Writer_PDF
 *  @copyright   Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
abstract class PHPExcel_Writer_PDF_Core extends PHPExcel_Writer_HTML
{
    /**
     * Temporary storage directory
     *
     * @var string
     */
    protected $_tempDir = '';

    /**
     * Font
     *
     * @var string
     */
    protected $_font = 'freesans';

    /**
     * Orientation (Over-ride)
     *
     * @var string
     */
    protected $_orientation    = NULL;

    /**
     * Paper size (Over-ride)
     *
     * @var int
     */
    protected $_paperSize    = NULL;


    /**
     * Temporary storage for Save Array Return type
     *
     * @var string
     */
	private $_saveArrayReturnType;

    /**
     * Paper Sizes xRef List
     *
     * @var array
     */
    protected static $_paperSizes = array(
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER
            => 'LETTER',                 
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_SMALL
            => 'LETTER',                 
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_TABLOID
            => array(792.00, 1224.00),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEDGER
            => array(1224.00, 792.00),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL
            => 'LEGAL',                  
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_STATEMENT
            => array(396.00, 612.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_EXECUTIVE
            => 'EXECUTIVE',              
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3
            => 'A3',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4
            => 'A4',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_SMALL
            => 'A4',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5
            => 'A5',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_B4
            => 'B4',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_B5
            => 'B5',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO
            => 'FOLIO',                  
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_QUARTO
            => array(609.45, 779.53),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_1
            => array(720.00, 1008.00),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_2
            => array(792.00, 1224.00),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_NOTE
            => 'LETTER',                 
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO9_ENVELOPE
            => array(279.00, 639.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO10_ENVELOPE
            => array(297.00, 684.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO11_ENVELOPE
            => array(324.00, 747.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO12_ENVELOPE
            => array(342.00, 792.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_NO14_ENVELOPE
            => array(360.00, 828.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_C
            => array(1224.00, 1584.00),  
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_D
            => array(1584.00, 2448.00),  
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_E
            => array(2448.00, 3168.00),  
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_DL_ENVELOPE
            => array(311.81, 623.62),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_C5_ENVELOPE
            => 'C5',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_C3_ENVELOPE
            => 'C3',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_C4_ENVELOPE
            => 'C4',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_C6_ENVELOPE
            => 'C6',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_C65_ENVELOPE
            => array(323.15, 649.13),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_B4_ENVELOPE
            => 'B4',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_B5_ENVELOPE
            => 'B5',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_B6_ENVELOPE
            => array(498.90, 354.33),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_ITALY_ENVELOPE
            => array(311.81, 651.97),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_MONARCH_ENVELOPE
            => array(279.00, 540.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_6_3_4_ENVELOPE
            => array(261.00, 468.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_US_STANDARD_FANFOLD
            => array(1071.00, 792.00),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_GERMAN_STANDARD_FANFOLD
            => array(612.00, 864.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_GERMAN_LEGAL_FANFOLD
            => 'FOLIO',                  
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_ISO_B4
            => 'B4',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_JAPANESE_DOUBLE_POSTCARD
            => array(566.93, 419.53),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_PAPER_1
            => array(648.00, 792.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_PAPER_2
            => array(720.00, 792.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_STANDARD_PAPER_3
            => array(1080.00, 792.00),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_INVITE_ENVELOPE
            => array(623.62, 623.62),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_EXTRA_PAPER
            => array(667.80, 864.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL_EXTRA_PAPER
            => array(667.80, 1080.00),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_TABLOID_EXTRA_PAPER
            => array(841.68, 1296.00),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_EXTRA_PAPER
            => array(668.98, 912.76),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_TRANSVERSE_PAPER
            => array(595.80, 792.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_TRANSVERSE_PAPER
            => 'A4',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_EXTRA_TRANSVERSE_PAPER
            => array(667.80, 864.00),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_SUPERA_SUPERA_A4_PAPER
            => array(643.46, 1009.13),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_SUPERB_SUPERB_A3_PAPER
            => array(864.57, 1380.47),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER_PLUS_PAPER
            => array(612.00, 913.68),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_PLUS_PAPER
            => array(595.28, 935.43),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5_TRANSVERSE_PAPER
            => 'A5',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_JIS_B5_TRANSVERSE_PAPER
            => array(515.91, 728.50),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_PAPER
            => array(912.76, 1261.42),   
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A5_EXTRA_PAPER
            => array(493.23, 666.14),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_ISO_B5_EXTRA_PAPER
            => array(569.76, 782.36),    
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A2_PAPER
            => 'A2',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_TRANSVERSE_PAPER
            => 'A3',                     
        PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_TRANSVERSE_PAPER
            => array(912.76, 1261.42)    
    );

    /**
     *  Create a new PHPExcel_Writer_PDF
     *
     *  @param     PHPExcel    $phpExcel    PHPExcel object
     */
    public function __construct(PHPExcel $phpExcel)
    {
        parent::__construct($phpExcel);
        $this->setUseInlineCss(TRUE);
        $this->_tempDir = PHPExcel_Shared_File::sys_get_temp_dir();
    }

    /**
     *  Get Font
     *
     *  @return string
     */
    public function getFont()
    {
        return $this->_font;
    }

    /**
     *  Set font. Examples:
     *      'arialunicid0-chinese-simplified'
     *      'arialunicid0-chinese-traditional'
     *      'arialunicid0-korean'
     *      'arialunicid0-japanese'
     *
     *  @param    string    $fontName
     */
    public function setFont($fontName)
    {
        $this->_font = $fontName;
        return $this;
    }

    /**
     *  Get Paper Size
     *
     *  @return int
     */
    public function getPaperSize()
    {
        return $this->_paperSize;
    }

    /**
     *  Set Paper Size
     *
     *  @param  string  $pValue Paper size
     *  @return PHPExcel_Writer_PDF
     */
    public function setPaperSize($pValue = PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER)
    {
        $this->_paperSize = $pValue;
        return $this;
    }

    /**
     *  Get Orientation
     *
     *  @return string
     */
    public function getOrientation()
    {
        return $this->_orientation;
    }

    /**
     *  Set Orientation
     *
     *  @param string $pValue  Page orientation
     *  @return PHPExcel_Writer_PDF
     */
    public function setOrientation($pValue = PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT)
    {
        $this->_orientation = $pValue;
        return $this;
    }

    /**
     *  Get temporary storage directory
     *
     *  @return string
     */
    public function getTempDir()
    {
        return $this->_tempDir;
    }

    /**
     *  Set temporary storage directory
     *
     *  @param     string        $pValue        Temporary storage directory
     *  @throws    PHPExcel_Writer_Exception    when directory does not exist
     *  @return    PHPExcel_Writer_PDF
     */
    public function setTempDir($pValue = '')
    {
        if (is_dir($pValue)) {
            $this->_tempDir = $pValue;
        } else {
            throw new PHPExcel_Writer_Exception("Directory does not exist: $pValue");
        }
        return $this;
    }

    /**
     *  Save PHPExcel to PDF file, pre-save
     *
     *  @param     string     $pFilename   Name of the file to save as
     *  @throws    PHPExcel_Writer_Exception
     */
    protected function prepareForSave($pFilename = NULL)
    {
        //  garbage collect
        $this->_phpExcel->garbageCollect();

        $this->_saveArrayReturnType = PHPExcel_Calculation::getArrayReturnType();
        PHPExcel_Calculation::setArrayReturnType(PHPExcel_Calculation::RETURN_ARRAY_AS_VALUE);

        //  Open file
        $fileHandle = fopen($pFilename, 'w');
        if ($fileHandle === FALSE) {
            throw new PHPExcel_Writer_Exception("Could not open file $pFilename for writing.");
        }

        //  Set PDF
        $this->_isPdf = TRUE;
        //  Build CSS
        $this->buildCSS(TRUE);

        return $fileHandle;
    }

    /**
     *  Save PHPExcel to PDF file, post-save
     *
     *  @param     resource      $fileHandle
     *  @throws    PHPExcel_Writer_Exception
     */
    protected function restoreStateAfterSave($fileHandle)
    {
        //  Close file
        fclose($fileHandle);

        PHPExcel_Calculation::setArrayReturnType($this->_saveArrayReturnType);
    }

}

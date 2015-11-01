<?php
/**
 * Simple excel generating from PHP5
 * 
 * This is one of my utility-classes.
 * 
 * The MIT License
 * 
 * Copyright (c) 2007 Oliver Schwarz
 * 
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package Utilities
 * @author Oliver Schwarz <oliver.schwarz@gmail.com>
 * @version 1.0
 */

/**
 * Generating excel documents on-the-fly from PHP5
 * 
 * Uses the excel XML-specification to generate a native
 * XML document, readable/processable by excel.
 * 
 * @package Utilities
 * @subpackage Excel
 * @author Oliver Schwarz <oliver.schwarz@vaicon.de>
 * @version 1.0
 *
  * @todo Add error handling (array corruption etc.)
 * @todo Write a wrapper method to do everything on-the-fly
 */

namespace Org\Util;

class ExcelXml
{

    /**
     * Header of excel document (prepended to the rows)
     * 
     * Copied from the excel xml-specs.
     * 
     * @access private
     * @var string
     */
    private $header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?\>
<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"
 xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
 xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"
 xmlns:html=\"http://www.w3.org/TR/REC-html40\">";

    /**
     * Footer of excel document (appended to the rows)
     * 
     * Copied from the excel xml-specs.
     * 
     * @access private
     * @var string
     */
    private $footer = "</Workbook>";

    /**
     * Document lines (rows in an array)
     * 
     * @access private
     * @var array
     */
    private $lines = array ();
    
    private $Index = 1;

    /**
     * Worksheet title
     *
     * Contains the title of a single worksheet
     *
     * @access private 
     * @var string
     */
    private $worksheet_title = "Table1";

    /**
     * Add a single row to the $document string
     * 
     * @access private
     * @param array 1-dimensional array
     * @todo Row-creation should be done by $this->addArray
     */
    private function addRow ($array)
    {

        // initialize all cells for this row
        $cells = "";

        // foreach key -> write value into cells
        foreach ($array as $k => $v):

            $cells .= "<Cell><Data ss:Type=\"String\">" . $v . "</Data></Cell>\n"; 

        endforeach;

        // transform $cells content into one row
        $this->lines[] = "<Row>\n" . $cells . "</Row>\n";

    }

    /**
     * Add an array to the document
     * 
     * This should be the only method needed to generate an excel
     * document.
     * 
     * @access public
     * @param array 2-dimensional array
     * @todo Can be transfered to __construct() later on
     */
    public function addArray ($array)
    {

        // run through the array and add them into rows
        foreach ($array as $k => $v):
            $this->addRow ($v);
        endforeach;

    }

    /**
     * Set the worksheet title
     * 
     * Checks the string for not allowed characters (:\/?*),
     * cuts it to maximum 31 characters and set the title. Damn
     * why are not-allowed chars nowhere to be found? Windows
     * help's no help...
     *
     * @access public
     * @param string $title Designed title
     */
    public function setWorksheetTitle ($title)
    {

        // strip out special chars first
        $title = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $title);

        // now cut it to the allowed length
        $title = substr ($title, 0, 31);

        // set title
        $this->worksheet_title = $title;

    }

    /**
     * Generate the excel file
     * 
     * Finally generates the excel file and uses the header() function
     * to deliver it to the browser.
     * 
     * @access public
     * @param string $filename Name of excel file to generate (...xls)
     */
    function generateXML ($filename)
    {

        // deliver header (as recommended in php manual)
        //header("Content-Type: text/html; charset=UTF-8");
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: inline; filename=\"" . $filename . ".xls\"");

        //add by sun
        header("Content-Transfer-Encoding: binary"); 
        header("Pragma: public"); 
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
        // end

        // print out document to the browser
        // need to use stripslashes for the damn ">"
        echo stripslashes ($this->header);
        echo "\n<Worksheet ss:Name=\"" . $this->worksheet_title . "\">\n<Table>\n";
        echo "<Column ss:Index=\"1\" ss:AutoFitWidth=\"0\" />\n";
        echo implode ("\n", $this->lines);
        echo "</Table>\n</Worksheet>\n";
        echo $this->footer;

    }
    
    /**
    * Add an array to the document
     * 
     * This should be the only method needed to generate an excel
     * document.
     * 
     * @access public
     * @param array 2-dimensional array
     * @todo Can be transfered to __construct() later on
     */
    public function addallArray ($array)
    {
        // run through the array and add them into rows
        foreach ($array as $k => $v):
            $this->addallRow ($v);
        endforeach;

    }
    /*
        $arr = array(
            'row'=>array(
                'cell'=>array(
                    'MergeAcross'=>'1',
                    'MergeDown'=>'3',
                    'Type'=>'Number',
                    'data'=>'网络房源总量',
                ),
            ),
            'row'=>array(
                'cell'=>array(
                    'data'=>'网络房源总量',
                ),
            ),
        );
    */
    public function addUMERows($arr)
    {
        if(is_array($arr))
        {
            $cells = '';
            foreach($arr as $Row)
            {
                foreach($Row as $Cell)
                {
                    $type = $Cell['Type']?$Cell['Type']:'String';
                    $MergeAcross = $Cell['MergeAcross']?" ss:MergeAcross=\"".$Cell['MergeAcross']."\"":'';
                    $MergeDown = $Cell['MergeDown']?" ss:MergeDown=\"".$Cell['MergeDown']."\"":'';
                    $cells .= "<Cell".$MergeAcross.$MergeDown."><Data ss:Type=\"".$type."\">" . $Cell['data'] . "</Data></Cell>\n";
                }
                $this->lines[] = "<Row>\n" . $cells . "</Row>\n";
                $cells = '';
            }
            
        }
    }
    /**
 * Add a single row to the $document string
     * 
     * @access private
     * @param array 1-dimensional array
     * @todo Row-creation should be done by $this->addArray
     */
    private function addallRow ($array)
    {

        // initialize all cells for this row
        $cells = "";
        $flag = false;
        // foreach key -> write value into cells
        foreach ($array as $k => $v):
            if (gettype($v) == 'array')
            {
                foreach ($v as $k1 => $v1):
                   if ($flag)
                   {
                          $cells .= "<Cell ss:Index=\"".$this->Index."\"><Data ss:Type=\"String\">" . $v1 . "</Data></Cell>\n"; 
                          $flag = false;
                   }
                   else 
                   {
                           $cells .= "<Cell><Data ss:Type=\"String\">" . $v1 . "</Data></Cell>\n"; 
                   }
                   
                endforeach;
            }
            else {
                 if ($v != "") 
                 {
                     if ($k =='mergedown' || $k == 'mergedown1' || $k == 'mergedown2' || $k == 'mergedown3')
                     {
                         $cells .= "<Cell ss:MergeDown=\"2\" ><Data ss:Type=\"String\">" . $v . "</Data></Cell>\n";
                     }
                     else 
                     {
                          $cells .= "<Cell><Data ss:Type=\"String\">" . $v . "</Data></Cell>\n"; 
                     }
                     
                     if ($k == 'mergedown')  $this->Index = 2;
                     if ($k == 'mergedown1') $this->Index = 3;
                     if ($k == 'mergedown2') $this->Index = 4;
                     if ($k == 'mergedown3') $this->Index = 5;
                     
                 }
                 else {
                     $flag = true;
                     
                 }
            }

        endforeach;

        // transform $cells content into one row
        $this->lines[] = "<Row>\n" . $cells . "</Row>\n"; 

    }

}

?>
<?php
/**
 * Custom Tables Joomla! 3.x Native Component
 * @author Ivan komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @license GNU/GPL
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');

class FindSimilarImage {
    
    static public function find($uploadedfile,$identity,$realtablename,$realfieldname_,$ImageFolder)
    {
        if($identity<0)
            $identity=0;
	
        require_once('compareimages.php');
    	$ci=new compareImages;
		
		$ext_list=array('png','jpg');
	
        $db = JFactory::getDBO();
        
        $query = 'SELECT '.$realfieldname.' AS photoid FROM '.$realtablename.' WHERE '.$realfieldname.'>0';
    	$db->setQuery($query);
        $photorows=$db->loadObjectList();
		
        foreach($photorows as $photorow)
        {
    	    $id=$photorow->photoid;

            if($id!=0)
            {
                foreach($ext_list as $ext)
                {
                    $image_file=$ImageFolder.DIRECTORY_SEPARATOR.'_original_'.$id.'.'.$ext;
                    if($image_file!=$uploadedfile)
                    {
                        if(file_exists($image_file))
                        {
                            $index=$ci->compare($uploadedfile,$image_file);
                            if($index<=$identity)
                                return $id;
                        }
                    }
                }//for each
            }//if
        }//foreach($photorows as $photorow)
    }//function
}//class

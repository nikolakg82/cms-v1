<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 10/25/2018
 * Time: 3:36 PM
 */

namespace cms\lib\publisher;

use fm\FM;
use fm\lib\help\File;

class AdminWorker
{
    protected $structure;

    public function __construct()
    {
        $this->structure = FM::includer(APP_CONFIG . 'admin/chapter.php', false);

        foreach ($this->structure as &$oneChapter)
        {
            foreach ($oneChapter['controllers'] as $strControllerKey)
            {
                if(File::exists(APP_CONFIG . 'admin/controllers/' . $strControllerKey . ".php"))
                    $oneChapter['controller_data'][$strControllerKey] = FM::includer(APP_CONFIG . 'admin/controllers/' . $strControllerKey . ".php", false);
            }
        }
    }

    public function getStructure()
    {
        return $this->structure;
    }
}
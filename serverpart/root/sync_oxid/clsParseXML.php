<?php

/************************************************
 *
 * MyCRM OXID-to-Sugar Connector
 *
 * The Program is provided AS IS, without warranty. You can redistribute it and/or modify it under the terms of the GNU
 * Affero General Public License Version 3 as published by the Free Software Foundation.
 *
 * For contact:
 * MyCRM GmbH
 * Hirschlandstrasse 150
 * 73730 Esslingen
 * Germany
 *
 * www.mycrm.de
 * info@mycrm.de
 ****************************************************/
/*
 * Modifications for Oxid 5.2.6 and SuiteCRM
 * 2016, Patrick Kirsch <pk@wellonga.de>
 *
 */
class OxidParseXML
{
    function GetChildren($vals, &$i)
    {
        $children = []; // Contains node data
        if (isset($vals[$i]['value'])) {
            $children['VALUE'] = $vals[$i]['value'];
        }

        while (++$i < count($vals)) {
            switch ($vals[$i]['type']) {

                case 'cdata':
                    if (isset($children['VALUE'])) {
                        $children['VALUE'] .= $vals[$i]['value'];
                    } else {
                        $children['VALUE'] = $vals[$i]['value'];
                    }
                    break;

                case 'complete':
                    if (isset($vals[$i]['attributes'])) {
                        $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                        $index                                      = count($children[$vals[$i]['tag']]) - 1;

                        if (isset($vals[$i]['value'])) {
                            $children[$vals[$i]['tag']][$index]['VALUE'] = $vals[$i]['value'];
                        } else {
                            $children[$vals[$i]['tag']][$index]['VALUE'] = '';
                        }
                    } else {
                        if (isset($vals[$i]['value'])) {
                            $children[$vals[$i]['tag']][]['VALUE'] = $vals[$i]['value'];
                        } else {
                            $children[$vals[$i]['tag']][]['VALUE'] = '';
                        }
                    }
                    break;

                case 'open':
                    if (isset($vals[$i]['attributes'])) {
                        $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                        $index                                      = count($children[$vals[$i]['tag']]) - 1;
                        $children[$vals[$i]['tag']][$index]         = array_merge($children[$vals[$i]['tag']][$index], $this->GetChildren($vals, $i));
                    } else {
                        $children[$vals[$i]['tag']][] = $this->GetChildren($vals, $i);
                    }
                    break;

                case 'close':
                    return $children;
            }
        }
    }

    function GetXMLTree($xmlloc)
    {
        $data   = $xmlloc;
        $parser = xml_parser_create('ISO-8859-1');
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $data, $vals, $index);

        $tree = [];
        $i    = 0;
        $newa = [];

        if (isset($vals[$i]['attributes'])) {
            $newa                                   = $this->GetChildren($vals, $i);
            $tree[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
            $index                                  = count($tree[$vals[$i]['tag']]) - 1;
            $tree[$vals[$i]['tag']][$index]         = array_merge($tree[$vals[$i]['tag']][$index], $newa);
        } else {
            $tree[$vals[$i]['tag']][] = $this->GetChildren($vals, $i);
        }

        $error = xml_error_string(xml_get_error_code($parser));
        if (strlen($error) > 5) {
            $msg = sprintf("SYNC_OXID PARSEXML Error: %s <br>", $error);
            error_log($msg);
            echo $msg;
        }

        xml_parser_free($parser);
        return $tree;
    }
}
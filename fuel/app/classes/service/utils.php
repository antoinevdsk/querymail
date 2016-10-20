<?php

namespace Service;

class Utils
{

    /**
     * Create a combo box
     * @param array $array
     * @param array $options
     * @param mixed $selectedItem
     * @param bool $disabled
     * @return string
     */
    static public function createSelect(array $array, $options, $selectedItem = false, $disabled = false)
    {
        $code = '<select';
        if (is_array($options)) {
            if (isset($options['firstText']) && isset($options['firstValue'])) {
                $firstText = $options['firstText'];
                $firstValue = $options['firstValue'];
                unset($options['firstText'], $options['firstValue']);
                $first = true;
            } else {
                $first = false;
            }
            foreach ($options as $attribute => $value) {
                $code .= ' ' . $attribute . '="' . $value . '"';
            }
        }
        if (!empty($disabled)) $code .= ' disabled="true" ';
        $code .= '>';
        if ($first) {
            $code .= '<option value="' . $firstValue . '">' . $firstText . '</option>';
        }
        if (is_array($array)) {
            $selectedItem = is_array($selectedItem) ? (array)$selectedItem : sprintf('%s', $selectedItem);
            foreach ($array as $value => $text) {
                $value = sprintf('%s', $value);
                if (is_scalar($text)) {
                    if (is_array($selectedItem)) {
                        $selected = in_array($value, $selectedItem) ? ' selected="selected"' : '';
                    } else {
                        $selected = ($value == $selectedItem) ? ' selected="selected"' : '';
                    }
                    $code .= '<option value="' . $value . '"' . $selected . '>' . $text . '</option>';
                } elseif (is_array($text)) {
                    $code .= '<optgroup label="' . $value . '">';
                    foreach ($text as $v => $t) {
                        if (is_array($selectedItem)) {
                            $selected = in_array($v, $selectedItem) ? ' selected="selected"' : '';
                        } else {
                            $selected = ($selectedItem == $v) ? ' selected="selected"' : '';
                        }
                        $code .= '<option value="' . $v . '"' . $selected . '>' . $t . '</option>';
                    }
                    $code .= '</optgroup>';
                }
            }
        }
        $code .= '</select>';
        return ($code);
    }

    /**
     * Create a fake combo box using checkbox
     * @param    array        conf for combo (key=>value)
     * @param    string    name use for each element
     * @param    array        array with selected values
     */
    static public function createCheckbox($array, $name, $selectedItem = false, $id = null, $readonlyWhenChecked = false)
    {
        $code = '';
        if (empty($id)) $id = $name;
        if (is_array($array)) {
            $i = 1;
            foreach ($array as $value => $text) {
                if (!is_array($text)) {
                    $checked = '';
                    if (is_array($selectedItem) && count($selectedItem) != 0) {
                        $checked = (in_array($value, $selectedItem)) ? ' checked="checked"' : '';
                        if (!empty($checked) && !empty($readonlyWhenChecked)) {
                            $checked .= ' onclick="this.checked=true" ';
                            $text = '<strong>' . $text . '</strong>';
                        }
                    }
                    $code .= '<li><input type="checkbox" value="' . $value . '" name="' . $name . '[]" id="' . $id . $i . '" ' . $checked . ' /> <label for="' . $id . $i . '">' . $text . '</label></li>';
                    $i++;
                } else {
                    $code .= '<li class="checkGroup">' . $value . '</li>';
                    foreach ($text as $v => $t) {
                        $checked = '';
                        if (is_array($selectedItem) && count($selectedItem) != 0) {
                            $checked = (in_array($v, $selectedItem)) ? ' checked="checked"' : '';
                            if (!empty($checked) && !empty($readonlyWhenChecked)) {
                                $checked .= ' onclick="this.checked=true" ';
                                $text = '<strong>' . $text . '</strong>';
                            }
                        }
                        $code .= '<li>&nbsp;&nbsp;&nbsp;<input type="checkbox" value="' . $v . '" name="' . $name . '[]" id="' . $id . $i . '" ' . $checked . ' /> <label for="' . $id . $i . '">' . $t . '</label></li>';
                        $i++;
                    }
                }
            }
            if (empty($code)) {
                $code = '---';
            } else {
                $code = '<ul class="checkbox">' . $code . '</ul>';
            }
        }
        return ($code);
    }


    public static function getAllConnexion()
    {
        $aConnexion = array();
        \Config::load('db', true);
        $aConfig = \Config::get('db');
        foreach ($aConfig as $sName => $aDb) {
            if ($sName != 'active' && $sName != 'default') {
                $aConnexion[$sName] = $sName;
            }
        }
        return $aConnexion;
    }

    public static function number($n)
    {
        $symbolRound = '';
        $iDecimal = 0;
        if ($n > 1000000) {
            $n /= 1000000;
            $iDecimal = 2;
            $symbolRound = 'M';
        } elseif ($n < 100 && strpos($n, '.') !== false) {
            $iDecimal = 3;
        }
        return number_format($n, $iDecimal, '.', ' ') . $symbolRound;
    }

    public static function diff($current, $last, $bDiffInPercent = 1)
    {
        $percent = 0;
        if ($bDiffInPercent == 1) {
            if ($last != 0) {
                $percent = (($current / $last) * 100) - 100;
                $percent = (int)round($percent);
            }
        } else {
            $percent = $current - $last;
        }
        return $percent;
    }

    public static function showDiff($current, $last, $bInverseColor = 0, $bDiffInPercent = 1)
    {
        $diff = self::diff($current, $last, $bDiffInPercent);
        if ($bInverseColor == 0) {
            $alert = ($diff < 0) ? '#FF0000' : '#008000';
        } else {
            $alert = ($diff < 0) ? '#008000' : '#FF0000';
        }
        $alert = ($diff == 0) ? '#0033FF' : $alert;
        $diff = ($diff > 0) ? '+' . $diff : $diff;
        if ($bDiffInPercent == '1') {
            if (abs($diff) > 10) {
                $diff = '<b>' . $diff . '%</b>';
            } else {
                $diff .= '%';
            }
        }
        return '<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td align="right" NOWRAP><font size="2">' . self::number($current) . '</font></td>
						<td width="40%" align="right" NOWRAP>
							<font color="' . $alert . '" size="1"><i>' . $diff . '</i></font>
						</td>
					</tr>
				</table>';
    }

}

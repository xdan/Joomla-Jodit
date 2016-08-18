<?php
/**
 * @copyright	Copyright (c) 2016 editors. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('JPATH_BASE') or die;
jimport('joomla.form.helper');

class JFormFieldButtons extends JFormField{
	public function attr($attr_name, $default = null){
		if (isset($this->element[$attr_name])) {
			return $this->element[$attr_name];
		} else {
			return $default;
		}
	}

	function getInput() {
		$default = explode(' ', $this->attr('defaultbuttons'));
		$buttons = explode(' ', $this->value);

        $k = 0;
        foreach ($buttons as $i=>$btn) {
            if ($btn === '|') {
                $buttons[$i] = 'separator' . $k;
                $k++;
            }
        }
        

        $toolbar_buttons = array();

        $k = 0;
        foreach ($default as $i=>$btn) {
            if ($btn === '|') {
                $default[$i] = $btn = 'separator' . $k;
                $k++;
            }
            $toolbar_buttons[$btn] = '<div id="jodit_buttons_' . $btn . '" class="jodit_buttons">
                <input data-name="' . $btn . '" ' . ((in_array($btn, $buttons)) ? 'checked="true"' : '') . ' class="hasTooltip" title="'.htmlspecialchars(Jtext::_('PLG_JODIT_FIELD_BUTTON_' . strtoupper($btn) . '_DESC')).'" type="checkbox"/><span><i data-btn="'.$btn.'" class="jodit_icon_faik"></i>' . Jtext::_('PLG_JODIT_FIELD_BUTTON_' . strtoupper($btn) . '_LABEL') . '</span>
            </div>';
        }

        $result = array();

        if (count($buttons)) {
            foreach ($buttons as $btn) {
                $result[] = $toolbar_buttons[$btn];
                unset($toolbar_buttons[$btn]);
            }
        }

        foreach ($toolbar_buttons as $btn) {
            $result[] = $btn;
        }
        
        JFactory::getDocument()->addScript(JURI::root() . 'plugins/editors/jodit/jodit/jodit.min.js');
        JFactory::getDocument()->addStyleSheet(JURI::root() . 'plugins/editors/jodit/jodit/jodit.min.css');

        $style  = '<style>
            .btn_box {
                margin-bottom:10px;
            }
            #jodit_buttons_joomla {
                -webkit-user-select:none;
                -moz-user-select:none;
                -ms-user-select:none;
                user-select:none
            }
            .jodit_buttons {
                padding:0 10px;
                background: #e6f1de;
                border-bottom: 1px solid #d6d6d6;
                color: #515559;
                line-height:36px;
                height:36px;
                width: 500px;
                cursor:pointer;
            }
            .jodit_buttons:hover {
                background: url('.JURI::root().'plugins/editors/jodit/fields/grippy_large.png) no-repeat 1px 50%;
                background-color: #ddf3ce;
                color: #979595;
                cursor: url('.JURI::root().'plugins/editors/jodit/fields/openhand.cur) 7 5, default;
            }
            .jodit_buttons:active {
                cursor: move;
            }
            .jodit_buttons.dragged {
                cursor: move;
                background: #bbd8a5;
                color: #fff;
            }
            .jodit_buttons input{
                vertical-align: middle;
                margin:0 5px 0 0;
            }
            .jodit_buttons span svg{
                vertical-align: text-bottom !important;
                margin-right: 10px;
            }
            .jodit_buttons span{
                display:inline-block;
            }
        </style>';
        $script  = '<script>
            (function ($) {
                var default_values = ' . json_encode($default) . ',
                    box = $("#jodit_buttons_joomla"),
                    i,
                    dragged_element,
                    dragged_html,
                    drag = false,
                    timeout,
                    recalc = function () {
                        clearTimeout(timeout);
                        timeout = setTimeout(function () {
                            var result = [], value, input;
                            box.find(".jodit_buttons").each(function () {
                                input = $(this).find("input");
                                if (input[0].checked) {
                                    value = input.data("name");
                                    result.push(value.match(/separator/) ? "|" : value);
                                }
                            });
                            $("#' . $this->id . '").val(result.join(" "));
                        });
                    };

                $(".btn_box button").on("click", function () {
                    switch ($(this).data("class")) {
                    case "select_all":
                        box.find("input").attr("checked", true);
                        break;
                    case "clear_all":
                        box.find("input").removeAttr("checked");
                        break;
                    case "select_default":
                        box.find("input").attr("checked", true);
                        for (i = 0; i < default_values.length; i += 1) {
                            box.append($("#jodit_buttons_" + default_values[i]));
                        }
                        break;
                    }
                    recalc();
                });

                box.find(".jodit_buttons")
                    .on("click", function (e) {
                        var input = $(this).find("input"), target = e.target;

                        if ((!input[0].checked && target !== input[0]) || (input[0].checked && target === input[0])) {
                            input.attr("checked", true);
                            input[0].checked = true;
                        } else {
                            input.removeAttr("checked");
                            input[0].checked = false;
                        }
                        
                        recalc();
                    })
                    .on("mousedown", function () {
                        if (!drag) {
                            drag = true;
                            dragged_element = this;
                            dragged_html = this.innerHTML;
                        }
                    })
                    .on("mouseenter", function () {
                        if (drag && !$(dragged_element).hasClass("dragged")) {
                            $(dragged_element).addClass("dragged");
                        }
                        if (drag && this !== dragged_element) {
                            dragged_element.innerHTML = this.innerHTML;
                            this.innerHTML = dragged_html;
                            $(dragged_element).removeClass("dragged")
                            $(this).addClass("dragged");
                            dragged_element = this;
                            recalc();
                        }
                    })

                $(window)
                    .on("mouseup", function () {
                        if (drag) {
                            drag = false;
                            box.find(".jodit_buttons")
                                .removeClass("dragged")
                            recalc();
                        }
                    });
                icons = new Jodit.modules.Icons();
                $(".jodit_icon_faik").each(function () {
                    $(this).replaceWith(icons.getSVGIcon($(this).data("btn")));
                });
            }(jQuery || Jodit.modules.Dom))
        </script>';

        return '
            <div class="btn_box">
                <button type="button" class="btn" data-class="select_all">' . jtext::_('PLG_JODIT_SELECT_ALL') . '</button>
                <button type="button" class="btn" data-class="select_default">' . jtext::_('PLG_JODIT_SELECT_DEFAULT') . '</button>
                <button type="button" class="btn" data-class="clear_all">' . jtext::_('PLG_JODIT_CLEAR_All') . '</button>
            </div>

            <input type="hidden" value="'.$this->value.'" name="'.$this->name.'" id="'.$this->id.'"/>
            <div class="jodit" id="jodit_buttons_joomla">
                ' . implode(PHP_EOL, $result) .'
            </div>' . $script . $style;
	}
}

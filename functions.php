<?php

/**
 * Edit options and return TweakTool instance
 */
function cgit_tweak_tool($options = []) {
    $tool = Cgit\TweakTool::getInstance();
    $tool->tweak($options);
    return $tool;
}

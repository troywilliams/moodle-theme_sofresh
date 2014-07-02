<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle's SoFresh theme, an example of how to make a Bootstrap theme
 *
 * DO NOT MODIFY THIS THEME!
 * COPY IT FIRST, THEN RENAME THE COPY AND MODIFY IT INSTEAD.
 *
 * For full information about creating Moodle themes, see:
 * http://docs.moodle.org/dev/Themes_2.0
 *
 * @package   theme_sofresh
 * @copyright 2014
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * 
 * @param moodle_page $page
 */
function theme_sofresh_page_init(moodle_page $page) {
    // 
    $page->requires->jquery();
    $page->requires->jquery_plugin('bootstrap', 'theme_sofresh');
}


//region_completely_docked($region, $output)
function theme_sofresh_grid_css() {
    global $PAGE, $OUTPUT;

    $hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
    $hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

    $sidepredocked = $PAGE->blocks->region_completely_docked('side-pre', $OUTPUT);
    $sidepostdocked = $PAGE->blocks->region_completely_docked('side-post', $OUTPUT);

    if ($hassidepre && $hassidepost && $sidepostdocked && $sidepredocked) {
        $regions = array('content' => 'col-sm-4 col-sm-push-4 col-md-6 col-md-push-3');
        $regions['pre'] = 'col-sm-4 col-sm-pull-4 col-md-3 col-md-pull-6';
        $regions['post'] = 'col-sm-4 col-md-3';
    } else if ($hassidepre && !$hassidepost && !$sidepostdocked) {
        $regions = array('content' => 'col-sm-8 col-sm-push-4 col-md-9 col-md-push-3');
        $regions['pre'] = 'col-sm-4 col-sm-pull-8 col-md-3 col-md-pull-9';
        $regions['post'] = 'emtpy';
    } else if (!$hassidepre && $hassidepost && !$sidepredocked) {// && !$sidepostdocked
        $regions = array('content' => 'col-sm-8 col-md-9');
        $regions['pre'] =  'empty';
        $regions['post'] = 'col-sm-4 col-md-3';
    } else if ($hassidepre && $hassidepost && $sidepostdocked && $sidepredocked) {
        $regions = array('content' => 'col-md-12');
        $regions['pre'] =  'empty';
        $regions['post'] = 'empty';
    } else if (!$hassidepre && !$hassidepost) {
        $regions = array('content' => 'col-md-12');
        $regions['pre'] =  'empty';
        $regions['post'] = 'empty';
    }
    
    return $regions;
}




function theme_sofresh_grid($hassidepre, $hassidepost) {
    global $PAGE;

    if ($hassidepre && $hassidepost) {
        $regions = array('content' => 'col-sm-4 col-sm-push-4 col-md-6 col-md-push-3');
        $regions['pre'] = 'col-sm-4 col-sm-pull-4 col-md-3 col-md-pull-6';
        $regions['post'] = 'col-sm-4 col-md-3';
    } else if ($hassidepre && !$hassidepost) {
        $regions = array('content' => 'col-sm-8 col-sm-push-4 col-md-9 col-md-push-3');
        $regions['pre'] = 'col-sm-4 col-sm-pull-8 col-md-3 col-md-pull-9';
        $regions['post'] = 'emtpy';
    } else if (!$hassidepre && $hassidepost) {
        $regions = array('content' => 'col-sm-8 col-md-9');
        $regions['pre'] =  'empty';
        $regions['post'] = 'col-sm-4 col-md-3';
    } else if (!$hassidepre && !$hassidepost) {
        $regions = array('content' => 'col-md-12');
        $regions['pre'] =  'empty';
        $regions['post'] = 'empty';
    }
    
    return $regions;
}

/**
 * Parses CSS before it is cached.
 *
 * This function can make alterations and replace patterns within the CSS.
 *
 * @param string $css The CSS
 * @param theme_config $theme The theme config object.
 * @return string The parsed CSS The parsed CSS.
 */
function theme_sofresh_process_css($css, $theme) {

    // Set the background image for the logo.
    $logo = $theme->setting_file_url('logo', 'logo');
    $css = theme_sofresh_set_logo($css, $logo);

    // Set custom CSS.
    if (!empty($theme->settings->customcss)) {
        $customcss = $theme->settings->customcss;
    } else {
        $customcss = null;
    }
    $css = theme_sofresh_set_customcss($css, $customcss);

    return $css;
}

/**
 * Adds the logo to CSS.
 *
 * @param string $css The CSS.
 * @param string $logo The URL of the logo.
 * @return string The parsed CSS
 */
function theme_sofresh_set_logo($css, $logo) {
    $tag = '[[setting:logo]]';
    $replacement = $logo;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_sofresh_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM and $filearea === 'logo') {
        $theme = theme_config::load('sofresh');
        return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Adds any custom CSS to the CSS before it is cached.
 *
 * @param string $css The original CSS.
 * @param string $customcss The custom CSS to add.
 * @return string The CSS which now contains our custom CSS.
 */
function theme_sofresh_set_customcss($css, $customcss) {
    $tag = '[[setting:customcss]]';
    $replacement = $customcss;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}

/**
 * Returns an object containing HTML for the areas affected by settings.
 *
 * Do not add SoFresh specific logic in here, child themes should be able to
 * rely on that function just by declaring settings with similar names.
 *
 * @param renderer_base $output Pass in $OUTPUT.
 * @param moodle_page $page Pass in $PAGE.
 * @return stdClass An object with the following properties:
 *      - navbarclass A CSS class to use on the navbar. By default ''.
 *      - heading HTML to use for the heading. A logo if one is selected or the default heading.
 *      - footnote HTML to use as a footnote. By default ''.
 */
function theme_sofresh_get_html_for_settings(renderer_base $output, moodle_page $page) {
    global $CFG;
    $return = new stdClass;

    $return->navbarclass = '';
    if (!empty($page->theme->settings->invert)) {
        $return->navbarclass .= ' navbar-inverse';
    }

    //if (!empty($page->theme->settings->logo)) {
     //   $return->heading = html_writer::link($CFG->wwwroot, '', array('title' => get_string('home'), 'class' => 'logo'));
    //} else {
        //$return->heading = $output->page_heading();
    //}

    $return->footnote = '';
    if (!empty($page->theme->settings->footnote)) {
        $return->footnote = '<div class="footnote text-center">'.$page->theme->settings->footnote.'</div>';
    }

    return $return;
}

/**
 * All theme functions should start with theme_sofresh_
 * @deprecated since 2.5.1
 */
function sofresh_process_css() {
    throw new coding_exception('Please call theme_'.__FUNCTION__.' instead of '.__FUNCTION__);
}

/**
 * All theme functions should start with theme_sofresh_
 * @deprecated since 2.5.1
 */
function sofresh_set_logo() {
    throw new coding_exception('Please call theme_'.__FUNCTION__.' instead of '.__FUNCTION__);
}

/**
 * All theme functions should start with theme_sofresh_
 * @deprecated since 2.5.1
 */
function sofresh_set_customcss() {
    throw new coding_exception('Please call theme_'.__FUNCTION__.' instead of '.__FUNCTION__);
}

class sofresh_bootstrap_dropdown extends custom_menu {
    /**
     * Adds a custom menu item as a child of this node given its properties.
     *
     * @param string $text
     * @param moodle_url $url
     * @param string $title
     * @param int $sort
     * @return boolean true
     */
    public function add_divider() {
        $key = count($this->children);
        if (empty($sort)) {
            $sort = $this->lastsort + 1;
        }
        $this->children[$key] =
        $this->lastsort = (int)$sort;
        return true;
    }
}

/**
 * Return sorted list of user courses  ...from blocks/course_overview/locallib.php
 *
 * @return array list of sorted courses and count of courses.
 */

function theme_sofresh_get_sorted_courses() {
    global $USER;

    $limit = 25;

    //$courses = enrol_get_my_courses('id, shortname, fullname, modinfo, sectioncache');
    $courses = enrol_get_my_courses(NULL, 'visible ASC,sortorder ASC');
    $site = get_site();

    if (array_key_exists($site->id,$courses)) {
        unset($courses[$site->id]);
    }

    foreach ($courses as $c) {
        if (isset($USER->lastcourseaccess[$c->id])) {
            $courses[$c->id]->lastaccess = $USER->lastcourseaccess[$c->id];
        } else {
            $courses[$c->id]->lastaccess = 0;
        }
    }

    // Get remote courses.
    $remotecourses = array();
    if (is_enabled_auth('mnet')) {
        $remotecourses = get_my_remotecourses();
    }
    // Remote courses will have -ve remoteid as key, so it can be differentiated from normal courses
    foreach ($remotecourses as $id => $val) {
        $remoteid = $val->remoteid * -1;
        $val->id = $remoteid;
        $courses[$remoteid] = $val;
    }

    $order = array();
    if (!is_null($usersortorder = get_user_preferences('course_overview_course_order'))) {
        $order = unserialize($usersortorder);
    }

    $sortedcourses = array();
    $counter = 0;
    // Get courses in sort order into list.
    foreach ($order as $key => $cid) {
        if (($counter >= $limit) && ($limit != 0)) {
            break;
        }

        // Make sure user is still enroled.
        if (isset($courses[$cid])) {
            $sortedcourses[$cid] = $courses[$cid];
            $counter++;
        }
    }
    // Append unsorted courses if limit allows
    foreach ($courses as $c) {
        if (($limit != 0) && ($counter >= $limit)) {
            break;
        }
        if (!in_array($c->id, $order)) {
            $sortedcourses[$c->id] = $c;
            $counter++;
        }
    }

    // From list extract site courses for overview
    $sitecourses = array();
    foreach ($sortedcourses as $key => $course) {
        if ($course->id > 0) {
            $sitecourses[$key] = $course;
        }
    }
    return ($sortedcourses);
}
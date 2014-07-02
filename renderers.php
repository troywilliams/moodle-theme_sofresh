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


require_once($CFG->dirroot . '/blocks/settings/renderer.php');
class theme_sofresh_block_settings_renderer extends block_settings_renderer {
    
    public function search_form(moodle_url $formtarget, $searchvalue) {
        static $count = 0;
        $formid = 'adminsearch';
        if ((++$count) > 1) {
            $formid .= $count;
        }

        $content = '';
        $content .= html_writer::start_tag('form', array('id' => $formid, 'method' => 'get', 'action' => $formtarget, 'role' => 'search'));
        $content .= html_writer::start_div('input-group');
        $content .= html_writer::tag('label', s(get_string('searchinsettings', 'admin')), array('for' => 'adminsearchquery', 'class' => 'accesshide'));
        $content .= html_writer::empty_tag('input', array('type' => 'text',
                                                          'name' => 'query',
                                                          'value' => s($searchvalue),
                                                          'class' => 'form-control'));
        $content .= html_writer::start_span('input-group-btn');
        $content .= html_writer::tag('button', get_string('search'), array('type' => 'submit', 
                                                                           'value' => s(get_string('search')),
                                                                           'class' => 'btn btn-primary'));
        $content .= html_writer::end_span();
        $content .= html_writer::end_div();
        $content .= html_writer::end_tag('form');
        return $content;
    }
}

require_once($CFG->dirroot . '/course/renderer.php');
class theme_sofresh_core_course_renderer extends core_course_renderer {
    
    public function search_form(moodle_url $formtarget, $searchvalue) {
        static $count = 0;
        $formid = 'adminsearch';
        if ((++$count) > 1) {
            $formid .= $count;
        }
        
        $content = '';
        $content .= html_writer::start_tag('form', array('id' => $formid, 'method' => 'get', 'action' => $formtarget, 'role' => 'search'));
        $content .= html_writer::start_div('input-group');
        $content .= html_writer::tag('label', s(get_string('searchinsettings', 'admin')), array('for' => 'adminsearchquery', 'class' => 'accesshide'));
        $content .= html_writer::empty_tag('input', array('type' => 'text',
                                                          'name' => 'query',
                                                          'value' => s($searchvalue),
                                                          'class' => 'form-control'));
        $content .= html_writer::start_span('input-group-btn');
        $content .= html_writer::tag('button', get_string('search'), array('type' => 'submit', 
                                                                           'value' => s(get_string('search')),
                                                                           'class' => 'btn btn-primary'));
        $content .= html_writer::end_span();
        $content .= html_writer::end_div();
        $content .= html_writer::end_tag('form');
        return $content;
    }
    /**
     * Renders html to display a course search form
     *
     * @param string $value default value to populate the search field
     * @param string $format display format - 'plain' (default), 'short' or 'navbar'
     * @return string
     */
    function course_search_form1($value = '', $format = 'plain') {
        static $count = 0;
        $formid = 'coursesearch';
        if ((++$count) > 1) {
            $formid .= $count;
        }

        switch ($format) {
            case 'navbar' :
                $formid = 'coursesearchnavbar';
                $inputid = 'navsearchbox';
                $inputsize = 20;
                break;
            case 'short' :
                $inputid = 'shortsearchbox';
                $inputsize = 12;
                break;
            default :
                $inputid = 'coursesearchbox';
                $inputsize = 30;
        }

        $formtarget = new moodle_url('/course/search.php');

        $content = '';
        $content .= html_writer::start_tag('form', array('id' => $formid, 'class'=>'navbar-form  pull-right','method' => 'get', 'action' => $formtarget, 'role' => 'search'));
        //$content .= html_writer::start_div('row');
        $content .= html_writer::start_div('input-group input-sm');
        $content .= html_writer::tag('label', get_string("searchcourses"), array('for' => 'adminsearchquery', 'class' => 'accesshide sr-only'));
        $content .= html_writer::empty_tag('input', array('type' => 'text',
                                                          'name' => 'query',
                                                          'value' => s($value),
                                                          'class' => 'form-control',
                                                          'size' => $inputsize));
        $content .= html_writer::start_span('input-group-btn');
        $content .= html_writer::tag('button', get_string('go'), array('type' => 'submit',
                                                                       'value' => get_string('go'),
                                                                       'class' => 'btn btn-primary'));
        $content .= html_writer::end_span();
        $content .= html_writer::end_div();
        //$content .= html_writer::end_div();
        $content .= html_writer::end_tag('form');
        return $content;

    }
}



class theme_sofresh_core_renderer extends core_renderer {


    public function navbar() {
        $breadcrumbs = '';
        foreach ($this->page->navbar->get_items() as $item) {
            $item->hideicon = true;
            $breadcrumbs .= '<li>'.$this->render($item).'</li>';
        }
        return "<ol class=breadcrumb>$breadcrumbs</ol>";
    }

    
    protected function render_user_menu2(custom_menu $menu) {
        global $CFG, $USER, $DB, $OUTPUT;

        $addusermenu = true;
        $addlangmenu = true;

        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2
        or empty($CFG->langmenu)
        or ($this->page->course != SITEID and !empty($this->page->course->lang))) {
            $addlangmenu = false;
        }

        if ($addlangmenu) {
            $language = $menu->add(get_string('language'), new moodle_url('#'), get_string('language'), 10000);
            foreach ($langs as $langtype => $langname) {
                $language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }

        if ($addusermenu) {
            if (isloggedin()) {
                $pic = $OUTPUT->user_picture($USER, array('size' => 32));
                $usermenu = $menu->add(fullname($USER) . $pic, new moodle_url('#'), fullname($USER), 10001);
                $usermenu->add(
                    '<span class="glyphicon glyphicon-off"></span>' . get_string('logout'),
                    new moodle_url('/login/logout.php', array('sesskey' => sesskey(), 'alt' => 'logout')),
                    get_string('logout')
                );

                $usermenu->add(
                    '<span class="glyphicon glyphicon-user"></span>' . get_string('viewprofile'),
                    new moodle_url('/user/profile.php', array('id' => $USER->id)),
                    get_string('viewprofile')
                );

                $usermenu->add(
                    '<span class="glyphicon glyphicon-cog"></span>' . get_string('editmyprofile'),
                    new moodle_url('/user/edit.php', array('id' => $USER->id)),
                    get_string('editmyprofile')
                );
            } else {
                $usermenu = $menu->add(get_string('login'), new moodle_url('/login/index.php'), get_string('login'), 10001);
            }
        }

        $content = '<ul class="nav navbar-nav navbar-right">';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item);
        }

        return $content.'</ul>';
    }


    public function user_menu() {
        global $USER, $OUTPUT, $DB;

        $course = $this->page->course;
        $context = context_course::instance($course->id);
        
        $logininfo = '';
        $content = '';
        $backtome = '';
        
        if (during_initial_install()) {
            return '';
        }

        if (empty($course->id)) {
            return '';
        }
        
        $loginurl = get_login_url();
        //$loginpage = ((string) $this->page->url === get_login_url());
        
        if (isloggedin()) {
            $displayuser = $USER;
            
            $logouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey(), 'alt' => 'logout'));
            
            $impersonating = '';
            if (\core\session\manager::is_loggedinas()) {
                $realuser = \core\session\manager::get_realuser();
                $loginastitle = get_string('loginas') . ' ' . fullname($realuser);
                $loginasurl = new moodle_url('/course/loginas.php', array('id' => $course->id, 'sesskey' => sesskey()));

                //$realuserfullname = fullname($realuser, true);
                $impersonating = 'Impersonating';
                $backtome = html_writer::link($loginasurl, '<i class="fa fa-undo"></i>', array('class' => 'login-link', 'title' => $loginastitle));
               // $backtome = html_writer::span($backtome);
            }
            $roleswitch = '';
            if (is_role_switched($course->id)) {
                $role = $DB->get_record('role', array('id' => $USER->access['rsw'][$context->path]));
                if ($role) {
                    $roleswitch = ' [' . role_get_name($role, $context) . ']';
                }
            }
            
            $displayname = fullname($displayuser);
            $logininfo = $impersonating . ' ' . $displayname . ' ' . $roleswitch . ' ' . $backtome;
            $logininfo = trim($logininfo);
            //$logininfo = html_writer::span($logininfo);
            //if (!empty($impersonating) or !empty($roleswitch)) {
            //}
            $displayavatar = $OUTPUT->user_picture($displayuser, array('size' => 100, 'link' => false));
            
            $content .= html_writer::start_tag('ul', array('class' => 'nav navbar-nav navbar-right user-menu'));
            $content .= html_writer::tag('li', $logininfo, array('class' => 'navbar-login-info'));
            $content .= html_writer::start_tag('li', array('class' => 'dropdown hidden-xs'));
            $linkattributes = array(
                    'href' => '#',
                    'class' => 'dropdown-toggle',
                    'data-toggle' => 'dropdown',
                    'title' => $displayname,
                );
            $content .= html_writer::start_tag('a', $linkattributes);
            $content .= html_writer::tag('span', $displayavatar, array('class' => 'avatar-small'));
            $content .= html_writer::tag('b', '', array('class' => 'caret'));
            $content .= html_writer::end_tag('a');
            $content .= html_writer::end_tag('li');

            $content .= html_writer::end_tag('ul');
            
            
            
        } else {
            $loginlink = html_writer::link(new moodle_url('/login/index.php'), get_string('login'));
            $content .= '<ul class="nav navbar-nav navbar-right user-menu">';
            $content .= html_writer::tag('li', $loginlink);
            $content .= html_writer::end_tag('ul');
        }
        
        return $content;
        
        
        
        if (isloggedin()) {
            $content .= '<ul class="nav navbar-nav navbar-right user-menu">';
            $content .= html_writer::start_tag('li', array('class' => 'dropdown'));
            $fullname = fullname($USER);
            $linkattributes = array(
                'href' => '#',
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'title' => $fullname,
            );
            
            $picture = $OUTPUT->user_picture($USER, array('size' => 100, 'link' => false));

            $content .= html_writer::start_tag('a', $linkattributes);
            $content .= html_writer::tag('span', $fullname . $picture, array('class' => 'avatar-small'));
            //$content .= $fullname . $picture;
            $content .= '<b class="caret"></b>';
            $content .= html_writer::end_tag('a');
            $content .= html_writer::start_tag('div', array('class' => 'dropdown-menu user-menu'));


            $logouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey(), 'alt' => 'logout'));
            $logoutlink = html_writer::link($logouturl, get_string('logout'), array('class' => 'btn btn-primary btn-xs pull-right'));


            //'<div ></div>';
            $content .= '<div class="um-container">';
            $content .= '<div class="left picture">'.$picture.'</div>';
            $content .= '<div class=""><b>'.$fullname.'</b></div>';
            $content .= '<p>'.$logoutlink.'</p>';
            $content .= '</div>';
            $content .= html_writer::end_tag('div');
            
            $content .= html_writer::end_tag('li');
            $content .= html_writer::end_tag('ul');
        } else {
            $loginlink = html_writer::link(new moodle_url('/login/index.php'), get_string('login'));
            $content .= '<ul class="nav navbar-nav navbar-right user-menu">';
            $content .= html_writer::tag('li', $loginlink);
            $content .= html_writer::end_tag('ul');
        }
        return $content;
    }


    /**
     * Renders a custom menu object (located in outputcomponents.php)
     *
     * The custom menu this method override the render_custom_menu function
     * in outputrenderers.php
     * @staticvar int $menucount
     * @param custom_menu $menu
     * @return string
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG, $USER;

        // TODO: eliminate this duplicated logic, it belongs in core, not
        // here. See MDL-39565.
        $content = '';
        $content = '<!-- This theme uses Bootstrap3, submenu\'s are not renderer. -->';
        //$content .= '<div class="nav navbar-nav navbar-fixed-top">';
        //$content .= '<ul>';
        $content .= '<ul class="nav navbar-nav">';
        if (isloggedin()) {
            $branchlabel = '<i class="fa fa-graduation-cap"></i>'.get_string('mycourses', 'theme_sofresh');
            $branchtitle = get_string('mycourses', 'theme_sofresh');
            $branchurl   = new moodle_url('/my/index.php');
            $branchsort  = 10000;
            $branchsort  = -10000;
            $branch = $menu->add($branchlabel, $branchurl, $branchtitle, $branchsort);
            $courses = theme_sofresh_get_sorted_courses();
            if ($courses) {//			if ($courses = enrol_get_my_courses(NULL, 'visible ASC,sortorder ASC')) {
                foreach ($courses as $course) {
                    if ($course->visible){
                        $branch->add(format_string($course->fullname), new moodle_url('/course/view.php?id='.$course->id), format_string($course->shortname));
                    }
                }
            } else {
                $branch->add('<em>'.get_string('noenrolments', 'theme_sofresh').'</em>',new moodle_url('/'),get_string('noenrolments', 'theme_sofresh'));//, -1
            }
            $branch->add('---');
            $cannotseepapers = html_writer::tag('b', get_string('cannotseepapers', 'theme_sofresh'), array('title' => get_string('cannotseepapers_tip', 'theme_sofresh')));
            $branch->add($cannotseepapers);
            //
        }
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item);
        }
        //$content .= $this->user_menu();
        $content .= '</ul>';
        //$content .= '</div>';
   
        return $content;
    }




    protected function render_custom_menu_item(custom_menu_item $menunode) {
        $content = '';

        if ($menunode->has_children()) {
            $content .= html_writer::start_tag('li', array('class' => 'dropdown'));
            $linkattributes = array(
                'href' => '#',
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'title' => $menunode->get_title(),
            );
            $content .= html_writer::start_tag('a', $linkattributes);
            $content .= $menunode->get_text();
            $content .= '<b class="caret"></b>';
            $content .= html_writer::end_tag('a');
            $content .= html_writer::start_tag('ul', array('class' => 'dropdown-menu'));
            foreach ($menunode->get_children() as $menunode) {
                // Menu divider or link?
                if ($menunode->get_text() === '---') {
                    $content .= html_writer::tag('li', '', array('class' => 'divider'));
                } else {
                    $content .= html_writer::start_tag('li');
                    $content .= html_writer::link($menunode->get_url(), $menunode->get_text(), array('title' => $menunode->get_title()));
                    $content .= html_writer::end_tag('li');
                }
            }
            $content .= html_writer::end_tag('ul');
            $content .= html_writer::end_tag('li');
        } else {
            $content .= html_writer::start_tag('li');
            $content .= html_writer::link($menunode->get_url(), $menunode->get_text(), array('title' => $menunode->get_title()));
            $content .= html_writer::end_tag('li');
        }

        return $content;
    }

    /**
    * Returns HTML to display a "Turn editing on/off" button in a form.
    *
    * @param moodle_url $url The URL + params to send through when clicking the button
    * @return string HTML the button
    * Written by G J Bernard
    */

    public function edit_button(moodle_url $url) {
        $url->param('sesskey', sesskey());
        if ($this->page->user_is_editing()) {
            $url->param('edit', 'off');
            $btn = 'btn-danger';
            $title = get_string('turneditingoff');
            $icon = 'fa-power-off';
        } else {
            $url->param('edit', 'on');
            $btn = 'btn-success';
            $title = get_string('turneditingon');
            $icon = 'fa-edit';
        }
        return html_writer::tag('a', html_writer::start_tag('i', array('class' => 'fa ' . $icon . ' fa-icon-white')).
               html_writer::end_tag('i'), array('href' => $url, 'class' => 'btn '.$btn, 'title' => $title));
    }


    public function actual_server_name() {
        static $servername;

        if (isset($servername)) {
            return $servername;
        }

        return $servername = gethostbyaddr($_SERVER['SERVER_ADDR']);
    }


    public function standard_footer_html51() {
        global $CFG, $SCRIPT;

        if (during_initial_install()) {
            // Debugging info can not work before install is finished,
            // in any case we do not want any links during installation!
            return '';
        }

        // This function is normally called from a layout.php file in {@link core_renderer::header()}
        // but some of the content won't be known until later, so we return a placeholder
        // for now. This will be replaced with the real content in {@link core_renderer::footer()}.
        $output = $this->unique_performance_info_token;
        if ($this->page->devicetypeinuse == 'legacy') {
            // The legacy theme is in use print the notification
            $output .= html_writer::tag('div', get_string('legacythemeinuse'), array('class'=>'legacythemeinuse'));
        }

        // Get links to switch device types (only shown for users not on a default device)
        $output .= $this->theme_switch_links();

        if (!empty($CFG->debugpageinfo)) {
            $output .= '<div class="performanceinfo pageinfo">This page is: ' . $this->page->debug_summary() . '</div>';
        }
        if (debugging(null, DEBUG_DEVELOPER) and has_capability('moodle/site:config', context_system::instance())) {  // Only in developer mode
            // Add link to profiling report if necessary
            if (function_exists('profiling_is_running') && profiling_is_running()) {
                $txt = get_string('profiledscript', 'admin');
                $title = get_string('profiledscriptview', 'admin');
                $url = $CFG->wwwroot . '/admin/tool/profiling/index.php?script=' . urlencode($SCRIPT);
                $link= '<a title="' . $title . '" href="' . $url . '">' . $txt . '</a>';
                $output .= '<div class="profilingfooter">' . $link . '</div>';
            }
            $purgeurl = new moodle_url('/admin/purgecaches.php', array('confirm' => 1,
                'sesskey' => sesskey(), 'returnurl' => $this->page->url->out_as_local_url(false)));
            $output .= '<div class="purgecaches">' .
                    html_writer::link($purgeurl, get_string('purgecaches', 'admin')) . '</div>';
        }

        //
        $output .= '<div class="pageinfo">'.gethostbyaddr($_SERVER['SERVER_ADDR']).'</div>';


        return $output;
    }

}
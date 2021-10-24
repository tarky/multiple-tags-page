<?php
/*
Plugin Name: Multiple tags page
Author: webfood
Plugin URI: http://webfood.info/
Description: Multiple tags page
Version: 0.1
Author URI: http://webfood.info/
Text Domain: Multiple tags page
Domain Path: /languages

License:
 Released under the GPL license
  http://www.gnu.org/copyleft/gpl.html

  Copyright 2021 (email : webfood.info@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
function multiple_tags() {
//複数タグのアーカイブでURLからスラッグを拾ってID・タグ名を取得。
$tagVar = get_query_var('tag');
if ( !empty($tagVar) ) {
    if ( strpos($tagVar, '+') || strpos($tagVar, ' ') )
        $separator = " + ";
    else if ( strpos($tagVar, ',') )
        $separator = " , ";
    $tagSlugs = $currentTerms = array();
    $tagSlugs = preg_split('(\+|,| )', $tagVar);
    foreach ($tagSlugs as $tagSlug)
        $currentTerms[] = get_term_by('slug', $tagSlug, 'post_tag');
}
//出力
if ( !empty($currentTerms) ) {
    $tagCount = count($currentTerms);
    $i = 0;
    foreach ($currentTerms as $currentTerm) {
        $currentTagName .= $currentTerm->name;
        $i++;
        if($i != $tagCount){
          $currentTagName .= $separator;
        }
    }
}
return $currentTagName;
}

// titleタグ変更　Cf. https://teratail.com/questions/168613
function change_document_title( $title ) {
  if ( is_tag() ) {
    $title = multiple_tags().'の記事';
  }
  return $title;
}

add_filter( 'pre_get_document_title', 'change_document_title' , 100);

add_filter('single_tag_title', function($title) {
return multiple_tags();
});

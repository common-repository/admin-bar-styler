/**
 * Description: Colorize the admin bar to match your site style, or make a visual distinction between different environments such as DEV, QA, UAT, PROD.
 *
 * This is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * See http://www.gnu.org/licenses/gpl-2.0.txt.
 *
 */
jQuery(document).ready(function($) {

	$(".admin-bar-styler-preset.red").click(function(){
		$("#admin-bar-styler-bg-color").val("#d32f2f");
		$("#admin-bar-styler-font-color").val("#dddddd");
		$("#admin-bar-styler-hover-color").val("#b71c1c");
	});
	$(".admin-bar-styler-preset.yellow").click(function(){
		$("#admin-bar-styler-bg-color").val("#fdd835");
		$("#admin-bar-styler-font-color").val("#555555");
		$("#admin-bar-styler-hover-color").val("#ffeb3b");
	});
	$(".admin-bar-styler-preset.green").click(function(){
		$("#admin-bar-styler-bg-color").val("#1b5e20");
		$("#admin-bar-styler-font-color").val("#dddddd");
		$("#admin-bar-styler-hover-color").val("#558b2f");
	});
	$(".admin-bar-styler-preset.blue").click(function(){
		$("#admin-bar-styler-bg-color").val("#1565c0");
		$("#admin-bar-styler-font-color").val("#dddddd");
		$("#admin-bar-styler-hover-color").val("#0d47a1");
	});
	$(".admin-bar-styler-preset.orange").click(function(){
		$("#admin-bar-styler-bg-color").val("#ff8040");
		$("#admin-bar-styler-font-color").val("#400040");
		$("#admin-bar-styler-hover-color").val("#e25747");
	});
	$(".admin-bar-styler-preset.purple").click(function(){
		$("#admin-bar-styler-bg-color").val("#400040");
		$("#admin-bar-styler-font-color").val("#4caf50");
		$("#admin-bar-styler-hover-color").val("#700070");
	});



});

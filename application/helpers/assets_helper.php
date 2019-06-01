<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	/**
	* @package for assets file often modified => javascript or css
	*/

	function assets_css_custom($filename,$echo=true)
	{
		$filenya = "assets/css/custom/".$filename;
		if (file_exists($filenya)) {
			$result = base_url().$filenya."?vfm=".filemtime($filenya);
			if ($echo) {
				echo '<link rel="stylesheet" href="'.$result.'" />';
			} else {
				return '<link rel="stylesheet" href="'.$result.'" />';
			}
		} else {
			echo "File css ".base_url($filenya)." not found..!!";
		}
	}

	function assets_css($filename,$echo=true)
	{
		$filenya = "assets/css/".$filename;
		if (file_exists($filenya)) {
			$result = base_url().$filenya."?vfm=".filemtime($filenya);
			if ($echo) {
				echo '<link rel="stylesheet" href="'.$result.'" />';
			} else {
				return '<link rel="stylesheet" href="'.$result.'" />';
			}
		} else {
			echo "File css ".base_url($filenya)." not found..!!";
		}
	}

	function assets_script($filename,$echo=true)
	{
		$filenya = "assets/js/".$filename;
		if (file_exists($filenya)) {
			$result = base_url().$filenya."?vfm=".filemtime($filenya);
			if ($echo) {
				echo '<script type="text/javascript" src="'.$result.'"></script>';
			} else {
				return '<script type="text/javascript" src="'.$result.'"></script>';
			}
		} else {
			echo "File script ".base_url($filenya)." not found..!!";
		}
	}

	function assets_script_custom($filename,$echo=true)
	{
		$filenya = "assets/js/custom/".$filename;
		if (file_exists($filenya)) {
			$result = base_url().$filenya."?vfm=".filemtime($filenya);
			if ($echo) {
				echo '<script type="text/javascript" src="'.$result.'"></script>';
			} else {
				return '<script type="text/javascript" src="'.$result.'"></script>';
			}
		} else {
			echo "File script ".base_url($filenya)." not found..!!";
		}
	}

	function assets_script_custom_master($filename,$echo=true)
	{
		$filenya = "master/".$filename;
		assets_script_custom($filenya,$echo);
	}

	function assets_script_custom_aktivitas($filename,$echo=true)
	{
		$filenya = "aktivitas/".$filename;
		assets_script_custom($filenya,$echo);
	}

	function assets_script_custom_aktivitasowner($filename,$echo=true)
	{
		$filenya = "aktivitasowner/".$filename;
		assets_script_custom($filenya,$echo);
	}

	function assets_script_custom_rwd($filename,$echo=true)
	{
		$filenya = "rwd/".$filename;
		assets_script_custom($filenya,$echo);
	}
	function assets_script_custom_approval($filename,$echo=true)
	{
		$filenya = "approval/".$filename;
		assets_script_custom($filenya,$echo);
	}

<?php

namespace Src;

class YoutubeDownloader {
	function video_id($url) {
		parse_str(parse_url($url,PHP_URL_QUERY),$vid);
		if ($vid['v']) {
			self::process_video($vid['v']);
		} else {
			self::process_video(ltrim(parse_url($url)['path'],'/'));
		}
	}
	private function process_video($vid) {
		parse_str(file_get_contents(urldecode('https%3A%2F%2Fyoutube.com%2Fget_video_info%2F%3Fvideo_id%3D' . $vid)),$result);
		$info                        = json_decode($result['player_response'],true);
		$result_array['title']       = $info['videoDetails']['title'];
		$result_array['thumbnail']   = urldecode('https%3A%2F%2Fi.ytimg.com%2Fvi%2F' . $vid . '%2Fmaxresdefault.jpg');
		$result_array['video&audio'] = self::get_result($info['streamingData']['formats']);
		$result_array['video|audio'] = self::get_result($info['streamingData']['adaptiveFormats']);
		print_r($result_array);
	}
	private function get_result($formats)
	{
		$result_array = [];
		
		if ($formats == true) {
			foreach ($formats as $i => $stream) {
				$video['url']     = urldecode($stream['url']);
				$video['type']    = explode(';',$stream['mimeType'])[0];
				$video['size']    = $stream['contentLength'];
				$video['quality'] = $stream['qualityLabel'];
				$result_array[$i] = $video;
			}
			return $result_array;
		}
	}
}
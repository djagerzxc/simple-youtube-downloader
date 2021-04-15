<?php

namespace Src;

class YoutubeDownloader {
	function video_id($url) {
		parse_str(parse_url($url,PHP_URL_QUERY),$vid);
		if ($vid['v']) return self::process_video($vid['v']);
		return self::process_video(ltrim(parse_url($url)['path'],'/'));
	}
	function get_size($size) {
		if (! empty($size)) {
			if (strlen($size) < 7) return round($size / 1024,2) . ' KB';
			return round($size / 1024 / 1024,2) . ' MB';
		} return '-';
	}
	private function process_video($vid) {
		parse_str(file_get_contents(urldecode(base64_decode(base64_decode(base64_decode('WVVoU01HTklUV3hOTUVWc1RXdFpiRTFyV2pWaU0xWXdaRmRLYkV4dFRuWmlVMVY1VW0xa2JHUkdPVEpoVjFKc1lqRTVjR0p0V25aS1ZFcEhTbFJPUjJSdGJHdGFWemxtWVZkUmJFMHdVVDA9')))) . $vid),$result);
		$info                        = json_decode($result['player_response']);
		$result_array['title']       = $info->videoDetails->title;
		$result_array['thumbnail']   = urldecode(base64_decode(base64_decode(base64_decode('WVVoU01HTklUV3hOTUVWc1RXdFpiRTFyV25CTWJtd3dZVmN4Ymt4dFRuWmlVMVY1VW01YWNFcFVTa2M9')))) . $vid . urldecode(base64_decode(base64_decode(base64_decode('U2xSS1IySlhSalJqYlZaNldrZFdiVmxZVm5Oa1F6VnhZMGRqUFE9PQ=='))));
		$result_array['video&audio'] = self::get_result($info->streamingData->formats);
		$result_array['video|audio'] = self::get_result($info->streamingData->adaptiveFormats);
		switch (true) {
			case empty($result_array['title']) :
				return 'INVALID URL';
			break;
			case isset($result_array['title']) && empty($result_array['video&audio']) :
				return 'COPYRIGHT';
			break;
			default :
				return $result_array;
		}
	}
	private function get_result($formats) {
		$result_array = array();
		if ($formats == true) {
			foreach ($formats as $i => $stream) {
				$video['url']          = urlencode(urldecode($stream->url));
				if (empty($video['url'])) $video['url'] = self::set_signature($stream->signatureCipher);
				$video['type']         = explode(';',$stream->mimeType)[0];
				$video['size_bit']     = $stream->contentLength;
				$video['size_convert'] = self::get_size($stream->contentLength);
				$video['quality']      = $stream->qualityLabel;
				if (empty($video['quality'])) $video['quality'] = '-';
				$result_array[$i]      = $video;
			}
			return $result_array;
		}
	}
	private function set_signature($sig) {
		parse_str(urldecode($sig),$sc);
		parse_str(parse_url($sc['url'],PHP_URL_QUERY),$expire);
		return urlencode(urldecode(http_build_query([
			str_replace('=' . $expire['expire'],'',$sc['url']) => $expire['expire'],
			'ei' => $sc['ei'],
			'ip' => $sc['ip'],
			'id' => $sc['id'],
			'itag' => $sc['itag'],
			'source' => $sc['source'],
			'requiressl' => $sc['requiressl'],
			'mh' => $sc['mh'],
			'mm' => $sc['mm'],
			'mn' => $sc['mn'],
			'ms' => $sc['ms'],
			'mv' => $sc['mv'],
			'mvi' => $sc['mvi'],
			'pl' => $sc['pl'],
			'inixwndbps' => $sc['initcwndbps'],
			'vprv' => $sc['vprv'],
			'mime' => $sc['mime'],
			'ns' => $sc['ns'],
			'gir' => $sc['gir'],
			'clen' => $sc['clen'],
			'ratebypass' => $sc['ratebypass'],
			'dur' => $sc['dur'],
			'lmt' => $sc['lmt'],
			'mt' => $sc['mt'],
			'fvip' => $sc['fvip'],
			'fexp' => $sc['fexp'],
			'c' => $sc['c'],
			'txp' => $sc['txp'],
			'n' => $sc['n'],
			'sparams' => $sc['sparams'],
			'sig' => $sc['s'],
			'lsparams' => $sc['lsparams'],
			'sp' => 'sig',
			'lsig' => $sc['lsig']])));
	}
}
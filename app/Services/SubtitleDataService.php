<?php namespace App\Services;

use App\Models\Subtitle;
use App\Services\Movie\SubtitleService;

class SubtitleDataService {
    public static function getSubtitle($imdbId, array $languages) {
        $subtitleService = new SubtitleService();
        $loginResp = $subtitleService->login(
            env('OPENSUBTITLE_USERNAME'),
            env('OPENSUBTITLE_PASSWORD'),
            'en',
            env('OPENSUBTITLE_USERAGENT'));
        $result = [];
        if (!$loginResp) return $result;
        $resp = $subtitleService->searchSubtitle($imdbId,$languages);
        if (empty($resp['data'])) {
            return $result;
        }
        foreach ($resp['data'] as $sub) {
            $s = new Subtitle();
            $s->imdb_id = $imdbId;
            $s->file_id = $sub['IDSubtitleFile'];
            $s->file_name = $sub['SubFileName'];
            $s->duration = $sub['SubLastTS'];
            $s->download_count = (int)$sub['SubDownloadsCnt'];
            $s->download_link = $sub['SubDownloadLink'];
            $s->file_size = (int)$sub['SubSize'];
            $s->add_date = $sub['SubAddDate'];
            $s->language = $sub['LanguageName'];
            $s->ISO639 = $sub['ISO639'];
            $s->ISO639_2 = $sub['SubLanguageID'];
            $s->save();
            $result[] = $s;
        }
        return $result;
    }

    public static function downloadSubtitle($subId) {
        $subtitleService = new SubtitleService();
        $loginResp = $subtitleService->login(
            env('OPENSUBTITLE_USERNAME'),
            env('OPENSUBTITLE_PASSWORD'),
            'en',
            env('OPENSUBTITLE_USERAGENT'));
        if (!$loginResp) return '';
        $resp = $subtitleService->downloadSubtitle([$subId]);
        if ($resp !== '') {
            $sub = Subtitle::find($subId);
            $sub->content = $resp;
            $sub->save();
        }
        return $resp;
    }
}
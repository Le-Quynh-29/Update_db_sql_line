<?php

namespace App\Http\Controllers;

use App\Models\HuyetDao;
use Illuminate\Http\Request;

class HuyetDaoController extends Controller
{
    public function getData()
    {
        // Retrieve all records
        $arrPhoiHuyet = HuyetDao::orderBy('_id', 'asc')->select('_id', 'phoihuyet', 'tenhuyet')->get();
        $arrNhanHuyet = [];
        $arrCheckFirst = ['Ủy Trung', 'Ủy Dương', 'Thủy Tuyền', 'Hoà Liêu', 'Nhĩ Hoà Liêu'];
        $arrCheckSecond = ['Uỷ Trung', 'Uỷ Dương', 'Thuỷ Tuyền', 'Hòa Liêu', 'Nhĩ Hòa Liêu'];
        foreach ($arrPhoiHuyet as $dataPhoiHuyet) {
            $data = HuyetDao::select('_id', 'tenhuyet')->orderBy('_id', 'asc')->get();
            $string = '';
            $explodePhoiHuyet = explode("\n", $dataPhoiHuyet->phoihuyet);
            foreach ($explodePhoiHuyet as $itemPhoiHuyet) {
                $row = '';
                foreach ($data as $value) {
                    $utf8TenHuyet = mb_convert_case(trim($value->tenhuyet), MB_CASE_TITLE, 'UTF-8');
                    $utf8PhoiHuyet = mb_convert_case($itemPhoiHuyet, MB_CASE_TITLE, 'UTF-8');
//                    if ($dataPhoiHuyet->_id == 150 && $value->_id == 146) {
//                        dd($utf8TenHuyet, $utf8PhoiHuyet);
//                    }
                    $firstIndex = mb_strpos($utf8PhoiHuyet, $utf8TenHuyet);
                    if ($firstIndex == false && in_array($utf8TenHuyet, $arrCheckFirst)) {
                        $index = array_search($utf8TenHuyet, $arrCheckFirst);
                        $firstIndex = mb_strpos($utf8PhoiHuyet, $arrCheckSecond[$index]);
                    }

                    if ($firstIndex) {
                        $strLen = mb_strlen($value->tenhuyet, 'utf-8');
                        if ($utf8TenHuyet == 'Âm Giao') {
                            $subStr = mb_substr($utf8PhoiHuyet, $firstIndex - 20, $strLen + 20);
                            $firstIndexOtherTamAmGiao = mb_strpos($subStr, 'Tam Âm Giao');
                            if ($firstIndexOtherTamAmGiao != false) {
                                while ($firstIndex === false) {
                                    $str = $firstIndex . ' ' . $strLen . ' ' . $value->_id . ',';
                                    $row .= $str;
                                    $firstIndex = mb_strpos($utf8PhoiHuyet, $utf8TenHuyet, $firstIndex + 1);
                                }
                            }
                        }
                        if ($utf8TenHuyet == 'Ngũ Lý') {
                            $subStr = mb_substr($utf8PhoiHuyet, $firstIndex - 5, $strLen + 20);
                            $firstIndexOtherTamAmGiao = mb_strpos($subStr, 'Thủ Ngũ Lý');
                            if ($firstIndexOtherTamAmGiao == false) {
                                while ($firstIndex !== false) {
                                    $str = $firstIndex . ' ' . $strLen . ' ' . $value->_id . ',';
                                    $row .= $str;
                                    $firstIndex = mb_strpos($utf8PhoiHuyet, $utf8TenHuyet, $firstIndex + 1);
                                }
                            }
                        } elseif ($utf8TenHuyet == 'Hoà Liêu') {
                            $subStr = mb_substr($utf8PhoiHuyet, $firstIndex - 5, $strLen + 20);
                            $firstIndexOtherTamAmGiao = mb_strpos($subStr, 'Nhĩ Hòa Liêu');
                            if ($firstIndexOtherTamAmGiao == false) {
                                while ($firstIndex !== false) {
                                    $str = $firstIndex . ' ' . $strLen . ' ' . $value->_id . ',';
                                    $row .= $str;
                                    $firstIndex = mb_strpos($utf8PhoiHuyet, $utf8TenHuyet, $firstIndex + 1);
                                }
                            }
                        } elseif ($utf8TenHuyet == 'Thông Cốc') {
                            $subStr = mb_substr($utf8PhoiHuyet, $firstIndex - 5, $strLen + 20);
                            $firstIndexOtherTamAmGiao = mb_strpos($subStr, 'Túc Thông Cốc');
                            if ($firstIndexOtherTamAmGiao == false) {
                                while ($firstIndex !== false) {
                                    $str = $firstIndex . ' ' . $strLen . ' ' . $value->_id . ',';
                                    $row .= $str;
                                    $firstIndex = mb_strpos($utf8PhoiHuyet, $utf8TenHuyet, $firstIndex + 1);
                                }
                            }
                        } elseif ($utf8TenHuyet == 'Quan Nguyên') {
                            $subStr = mb_substr($utf8PhoiHuyet, $firstIndex, $strLen + 20);
                            $firstIndexOtherTamAmGiao = mb_strpos($subStr, 'Quan Nguyên Du');
                            if ($firstIndexOtherTamAmGiao == false) {
                                while ($firstIndex !== false) {
                                    $str = $firstIndex . ' ' . $strLen . ' ' . $value->_id . ',';
                                    $row .= $str;
                                    $firstIndex = mb_strpos($utf8PhoiHuyet, $utf8TenHuyet, $firstIndex + 1);
                                }
                            }
                        } else {
                            while ($firstIndex !== false) {
                                $str = $firstIndex . ' ' . $strLen . ' ' . $value->_id . ',';
                                $row .= $str;
                                $firstIndex = mb_strpos($utf8PhoiHuyet, $utf8TenHuyet, $firstIndex + 1);
                            }
                        }
                    }
                }

                if ($row == '') {
                    $string .= '0 0 0,' . "\r\n";
                } else {
                    $string .= $row . "\r\n";
                }
            }
            HuyetDao::where('_id', $dataPhoiHuyet->_id)->update([
                'nhanhuyet' => $string
            ]);
            $res = new \stdClass();
            $res->id = $dataPhoiHuyet->_id;
            $res->tenhuyet = $dataPhoiHuyet->tenhuyet;
            $res->phoihuyet = $dataPhoiHuyet->phoihuyet;
            $res->nhanhuyet = $string;
            array_push($arrNhanHuyet, $res);
        }
        dd('update success');
        return view('huyetdao', compact('arrNhanHuyet'));
    }
}

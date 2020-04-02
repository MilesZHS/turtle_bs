<?php


namespace app\admin\controller;
use app\common\lib\Upload;
use think\Controller;
use think\Exception;
use think\Request;

/**
 * Class Image
 * @package app\admin\controller
 * 后台图片上传相关逻辑
 */
class Image extends Controller
{
//        public function upload() {
//            try {
//                $image = Upload::image();
//            }catch (\Exception $e) {
//                echo json_encode(['status' => 0, 'message' => $e->getMessage()]);
//            }
//            if($image) {
//                $data = [
//                    'status' => 1,
//                    'message' => 'OK',
//                    'data' => config('qiniu.image_url').'/'.$image,
//                ];
//                echo json_encode($data);exit;
//            }else {
//                echo json_encode(['status' => 0, 'message' => '上传失败']);
//            }
//        }
    

    public function upload(){
        $file = Request::instance()->file('file');
        //把图片上传到指定文件夹
        $info = $file->move('upload');

        if ($info && $info->getPathname()){
            $data = [
                'status'    =>  1,
                'message'   =>  'ok',
                'data'      =>  '/'.$info->getPathname()
            ];
            echo json_encode($data);exit;
        }
        echo json_encode(['status' => 0, 'message' => '上传失败']);

    }


}
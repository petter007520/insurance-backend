<?php namespace Stevenyangecho\UEditor\Uploader;

use App\Seting;
use Stevenyangecho\UEditor\Uploader\Upload;
use Cache;
use Image;

/**
 *
 *
 * Class UploadFile
 *
 * 文件/图像普通上传
 *
 * @package Stevenyangecho\UEditor\Uploader
 */
class UploadFile  extends Upload{
    use UploadQiniu;
    public function doUpload()
    {


        $file = $this->request->file($this->fileField);
        if (empty($file)) {
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
            return false;
        }
        if (!$file->isValid()) {
            $this->stateInfo = $this->getStateInfo($file->getError());
            return false;

        }

        $this->file = $file;

        $this->oriName = $this->file->getClientOriginalName();

        $this->fileSize = $this->file->getSize();
        $this->fileType = $this->getFileExt();

        $this->fullName = $this->getFullName();


        $this->filePath = $this->getFilePath();

        $this->fileName = basename($this->filePath);


        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return false;
        }
        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return false;
        }

        //生成水印

        $config=Cache::get('thumbsize');
        $water_on=Cache::get('water');
        $watermark=Cache::get('watermark');
        $waterposition=Cache::get('waterposition');

        $img = Image::make($this->file->getPathname());

        if($water_on=='开启' || $water_on=='on'){

            $img->insert("uploads/".$watermark, $waterposition, 10, 10)->save($this->file->getPathname());

        }


        if(config('UEditorUpload.core.mode')=='local'){
            try {
                $this->file->move(dirname($this->filePath), $this->fileName);

                $this->stateInfo = $this->stateMap[0];

            } catch (FileException $exception) {
                $this->stateInfo = $this->getStateInfo("ERROR_WRITE_CONTENT");
                return false;
            }

        }else if(config('UEditorUpload.core.mode')=='qiniu'){

            $content=file_get_contents($this->file->getPathname());
            return $this->uploadQiniu($this->filePath,$content);

        }else{
            $this->stateInfo = $this->getStateInfo("ERROR_UNKNOWN_MODE");
            return false;
        }




        return true;

    }
}

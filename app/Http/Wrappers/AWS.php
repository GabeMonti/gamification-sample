<?php
/**
 * Created by Phpstorm.
 * Date: 18/09/18
 * Time: 17:35
 */

namespace App\Http\Wrappers;

use Aws\Sdk;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\App;

class AWS extends BaseWrapperController implements BaseWrapperInterface
{
    private $s3;

    public function __construct()
    {
        $this->s3 = App::make('aws')->createClient('s3');
    }

    public function upload($file, $fileName)
    {
        if ($file instanceof Illuminate\Http\UploadedFile) {
          $contentType = $file->getClientMimeType();
        } else {
          $contentType = 'image/' . $file->getExtension();
        }
        $s3Response = $this->s3->putObject([
            'Key' => $fileName,
            'Bucket' => env('AWS_BUCKET', 'dev'),
            'ContentType' => $contentType,
            'Body' => file_get_contents($file),
            'ACL' => 'public-read'
         ]);

        return $s3Response['ObjectURL'];
    }

    public function delete($fileName)
    {
        $s3Response = $this->s3->deleteObject([
            'Bucket' =>  env('AWS_BUCKET', 'dev'),
            'Key'    => $fileName
        ]);
        return $s3Response;
    }

    public function find($filename)
    {
        $s3Response = $this->s3->getObject([
           'Bucket' => env('AWS_BUCKET', 'dev'),
            'Key'   => $filename
        ]);
        return $s3Response;
    }
}

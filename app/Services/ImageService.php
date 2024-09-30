<?php

namespace App\Services;

use Bepsvpt\Blurhash\Facades\BlurHash;

class ImageService
{

    public function storeImage($model, $image, $collection)
    {
        try {
            if (!isset($image)) return;
            else if (str_contains($image, config('app.url'))) {
                $image = str_replace(config('app.url'), '', $image);
                $hash = BlurHash::encode(storage_path('images/temp/') . $image);


                $mediaModel = $model->addMedia(storage_path('images/temp/') . $image)->preservingOriginal()->toMediaCollection($collection);

            } else {
                $hash = BlurHash::encode(storage_path('images/temp/') . $image);

                $mediaModel = $model->addMedia(storage_path('images/temp/') . $image)->preservingOriginal()->toMediaCollection($collection);
            }
            $mediaModel->setCustomProperty('hash', $hash);
            $mediaModel->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateMedia($model, $image, $collection)
    {
        try {
            if (!isset($image)) {
                return;
            }

            // Check if the user already has an image in the specified collection
            $existingMedia = $model->getFirstMedia($collection);

            if ($existingMedia &&  substr_replace($image,'',-4)==$existingMedia->name) {
                // Remove the existing image
                return;
            }else if($existingMedia ){
                try {
                    $existingMedia->delete();
                }
            catch (\Exception $e){}
            }



            // Process the new image
            if (str_contains($image, config('app.url'))) {
                $image = str_replace(config('app.url'), '', $image);
                $hash = BlurHash::encode(storage_path('images/temp/') . $image);

                $mediaModel = $model->addMedia(storage_path('images/temp/') . $image)
                    ->preservingOriginal()
                    ->toMediaCollection($collection);
            } else {
                $hash = BlurHash::encode(storage_path('images/temp/') . $image);

                $mediaModel = $model->addMedia(storage_path('images/temp/') . $image)
                    ->preservingOriginal()
                    ->toMediaCollection($collection);
            }

            // Set custom property and save
            $mediaModel->setCustomProperty('hash', $hash);
            $mediaModel->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

}

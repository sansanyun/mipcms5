<?php
Route::rule(['xml/:id' => ['index/Index/xml?id=:id',['ext'=>'xml'],['id'=>'\d+']]]);
<?php

namespace App\Services;

use Illuminate\Http\Request;

interface IPostService
{
    public function create(Request $request);

    public function update(Request $request, int $id);

    public function delete(int $id);
}

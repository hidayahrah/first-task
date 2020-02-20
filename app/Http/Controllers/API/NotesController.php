<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Note;

class NotesController extends Controller
{
    private function notFoundMessage()
    {

        return [
            'code' => 404,
            'message' => 'Note not found',
            'success' => false,
        ];

    }

    private function successfulMessage($code, $message, $status, $count, $payload)
    {

        return [
            'code' => $code,
            'message' => $message,
            'success' => $status,
            'count' => $count,
            'data' => $payload,
        ];

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allNotes()
    {

        $notes = Note::all();
        $response = $this->successfulMessage(200, 'Successfully', true, $notes->count(), $notes);

        return response($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //create new article
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['data'] = $validator->messages();
            return $response;
        }

        $note = new Note;
        $note->name = $request->name;
        $note->save();
        $response = $this->successfulMessage(201, 'Successfully created', true, $note->count(), $note);
        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permanentDelete($id)
    {

        $note = Note::destroy($id);
        if ($note) {

            $response = $this->successfulMessage(200, 'Successfully deleted', true, 0, $note);

        } else {

            $response = $this->notFoundMessage();
        }

        return response($response);
    }

    public function softDelete($id)
    {

        $note = Note::destroy($id);
        if ($note) {
            $response = $this->successfulMessage(200, 'Successfully deleted', true,0, $note);
        } else {

            $response = $this->notFoundMessage();

        }
        return response($response);
    }

    //returns both non-deleted and softdeleted
    public function notesWithSoftDelete()
    {

        $notes = Note::withTrashed()->get();
        $response = $this->successfulMessage(200, 'Successfully', true, $notes->count(), $notes);
        return response($response);

    }

    public function softDeleted()
    {
        $notes = Note::onlyTrashed()->get();

        $response = $this->successfulMessage(200, 'Successfully', true, $notes->count(), $notes);
        return response($response);
    }

    public function restore($id)
    {

        $note = Note::onlyTrashed()->find($id);

        if (!is_null($note)) {

            $note->restore();
            $response = $this->successfulMessage(200, 'Successfully restored', true, $note->count(), $note);
        } else {

            return response($response);
        }
        return response($response);
    }

    public function permanentDeleteSoftDeleted($id)
    {
        $note = Note::onlyTrashed()->find($id);

        if (!is_null($note)) {

            $note->forceDelete();
            $response = $this->successfulMessage(200, 'Successfully deleted', true, 0, $note);
        } else {

            return response($response);
        }
        return response($response);
    }
}
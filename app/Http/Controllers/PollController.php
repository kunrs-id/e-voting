<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\Photo;
use App\Models\Choice;
use App\Models\Mail;
use App\Models\Reply;


class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $poll = Poll::where('id','>',0)->count();
      
      return view('admin.create',compact('poll'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate(
        [
          'title' => 'required',
          'description' => 'required',
          'deadline' => 'required',

        ],
        [
          'title.required' => 'Kolom judul harus di isi.',
          'description.required' => 'Kolom descripsi harus di isi.',
          'deadline.required' => 'Kolom Batas waktu harus di isi.'
        ]);

      $data = new Poll;

      $data->title = $request->title;
      $data->slug = Str::slug($request->title,'-');
      $data->description = $request->description;
      $data->deadline = $request->deadline;
      $data->created_by = auth()->user()->id;
      $data->save();
      $request->validate(
        [
          "choice" => "required",
          "misi" => "required",
          "visi" => "required"
        ],
        [
          "choice.required" => "Kolom ini harus di isi",
          "misi.required" => "Kolom misi harus di isi",
          "visi.required" => "Kolom visi harus di isi" 
        ]);

      $pilih = $request->choice;
      $url = $request->file('gambar');
      $visi = $request->visi;
      $misi = $request->misi;

      $request->sampul = $url;
      foreach ($pilih as $i => $value) {
        $choice = new Choice;
        $choice->name = $value;
        $choice->sampul = $url[$i]->store('gambar');
        $choice->visi = $visi[$i];
        $choice->misi = $misi[$i];
        $choice->poll_id = $data->id;
        $choice->save();

      }



      return redirect('/admin')->with('status' ,'Data berhasil di buat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $id = Poll::find($id);
      return view('admin.updatepoll',compact('id'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $request->validate(
        [
          'title' => 'required',
          'description' => 'required',
          'deadline' => 'required',

        ],
        [
          'title.required' => 'Kolom judul harus di isi.',
          'description.required' => 'Kolom descripsi harus di isi.',
          'deadline.required' => 'Kolom Batas waktu harus di isi.'
        ]);


      Poll::where('id',$id)
      ->update([
        'title' => $request->title,
        'description' => $request->description,
        'deadline' => $request->deadline


      ]);
      return redirect('/admin')->with('status' ,'Data berhasil di update');
    }



    public function editchoice($id)
    {
      $id = Choice::find($id);
      return view('admin.editchoice',compact('id'));
    }

    public function updatechoice(Request $request,$id)
    {   
      $request->validate(
        [
          "choice" => "required",
          'visi' => "required",
          'misi' => "required"
        ],
        [
          "choice.required" => "Kolom ini harus di isi",
          "visi.required" => "Kolom visi harus di isi",
          "misi.required" => "Kolom misi harus di isi"
        ]); 


      if($request->file('sampul') == null)
      {
        Choice::where('id',$id)
        ->update([
          'name' => $request->choice,
          'visi' => $request->visi,
          'misi' => $request->misi
        ]);
        return redirect('/admin')->with('status','Choice berhasil di update');

      }else{
        $sampul = $request->file('sampul');
        $sampul = $request->sampul;
        Choice::where('id',$id)
        ->update([
          'name' => $request->choice,
          'visi' => $request->visi,
          'misi' => $request->misi,
          'sampul' => $sampul->store('gambar')
        ]);

      }



      return redirect('/admin')->with('status','Choice berhasil di update');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(poll $poll)
    {
     $poll = Poll::destroy($poll->id);

     return redirect('admin')->with('delete','Data telah berhasil di hapus');
   }

   //vote
   public function resultvote()
   {
    $poll = Poll::all();
    return view('admin.resultvote',['poll' => $poll]);
  }

  public function result($id)
  {
    $data  = Poll::where('id',$id)->with('choice.vote')->first();
    $jml = 0;
    foreach($data->choice as $ch) {
      $jml += $ch->vote->count();
    }

    return view('admin.result',['data' => $data,'jml' => $jml]);

  }

  public function mails()
  {
    $cek = Reply::where('id_pesan',">" ,0)->get();

    $mail = Mail::all();

    return view('admin.mail',compact('mail','cek'));

  }

  public function reply($id)
  {
    $mail = Mail::find($id);
    return view('admin.balas',compact('mail'));

  }

  public function sendreply(Request $request)
  {

    $request->validate([
      'balaspesan' => 'required',
      'user' => 'required',
      'idpesan' => 'required'
    ]);

    date_default_timezone_set('Asia/Jakarta');
    $time = date("H:i:s");
    $data = new Reply;

    $data->balasan = $request->balaspesan;
    $data->user_id = $request->user;
    $data->tanggal = date('Y-m-d');
    $data->waktu = $time;
    $data->id_pesan = $request->idpesan;

    $data->save();
    return redirect('/mails')->with('status','Pesan berhasil di balas');


  }

  public function contact()
  {
    return view('admin.contact');
  }  


  public function polldelete($id)
  {
    $poll = Poll::where('id',$id)->get();

    return view('admin.hapus.polldelete',compact('poll'));
  }

}

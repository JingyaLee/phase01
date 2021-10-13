<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// ↓2行追加
use Validator;
use App\Models\Tweet;
use Auth;
use App\Models\User;

class TweetController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
{
  // ↓編集
  $tweets = Tweet::paginate(5);
  return view('tweet.index', [
    'tweets' => $tweets
  ]);
}


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // ↓追加
    return view('tweet.create');
  }

  // 以降は変更なし






// <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class TweetController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function index()
//     {
//         //
//     }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function create()
//     {
//         //
//     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // バリデーション
      $validator = Validator::make($request->all(), [
        'tweet' => 'required | max:191',
        'description' => 'required',
        'image' => 'image'
      ]);
      // バリデーション:エラー
      if ($validator->fails()) {
        return redirect()
          ->route('tweet.create')
          ->withInput()
          ->withErrors($validator);
      }
      // if (request('image')){
      //   $filename=request()->flie('image')->getClinetOriginalName();
      //   $inputs['image']=request('image')->storeAs('public/images',$filename)
      // }
    // ↓編集 フォームから送信されてきたデータとユーザIDをマージし，DBにinsertする
    $data = $request->merge(['user_id' => Auth::user()->id])->all();
    $result = Tweet::create($data);
    $img = $request->imgpath->store('');
    $titlename = $request->imgpath->getClientOriginalName();
    $filename = $request->imgpath->getClientOriginalName();
    $img = $request->imgpath->storeAs('',$filename,'public');
    $user = new Tweetcontroller();
    $data = $user->create(['imgpath'=>$img]);
    //dd($img);

    // tweet.index」にリクエスト送信（一覧ページに移動）
    return redirect()->route('tweet.index');
  }

    
  
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $tweet = Tweet::find($id);
      return view('tweet.show', ['tweet' => $tweet]);
    }
  


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $tweet = Tweet::find($id);
      return view('tweet.edit', ['tweet' => $tweet]);
    }
    

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function update(Request $request, $id)
//     {
//         //
//     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $result = Tweet::find($id)->delete();
      return redirect()->route('tweet.index');
    }

    public function mydata()
    {
      // Userモデルに定義した関数を実行する．
      $tweets = User::find(Auth::user()->id)->mytweets;
      return view('tweet.index', [
        'tweets' => $tweets
      ]);
    }    
}

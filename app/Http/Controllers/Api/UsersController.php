<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; 

use Illuminate\Support\Facades\Storage;


class UsersController extends Controller
{
    private $usuarios;

    public function __construct(User $usr)
    {
        $this->usuarios = $usr;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = $this->usuarios->all();
        return response()->json(['data' => $usuarios], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validando = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'phone' => 'required|string',
            'photo' => 'file|max:2048|mimes:jpg,png',
            'password' => 'required|string|min:8'
        ]);

        if ( $validando->fails() ) {
            return response()->json($validando->errors());
        }

        $dados = $request->all();

        // upload da foto do usuário
        if ( $request->file('photo') ) {
            // gera um novo nome ao arquivo
            $nomeArquivo = uniqid(date('HisYmd'));  
            $extensao = $request->photo->extension();
            $arquivoFinal = "{$nomeArquivo}.{$extensao}";
        
            $upload = $request->photo->storeAs('photos', $arquivoFinal);

            if ( !$upload ) {
                return response()->json(['error' => 'Ops! Houve uma falha ao realizar o upload da Foto!'], 403);
            }

            $dados['photo'] = $arquivoFinal;
        }

        $dados['password'] = Hash::make($request->password);
        
        $response = $this->usuarios->create($dados);

        // em caso de sucesso no cadastro do usuário, sistema registra o token de acesso a api
        if ( $response ) {
            return response()->json(['success' => 'Usuário cadastrado com sucesso!', 'data' => $response], 201);
        } else {
            return response()->json(['error' => 'Ops! Houve um erro ao cadastrar usuário, por favor verifique os campos informados e tente novamente!'], 403);
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $usuario = $this->usuarios->find($id);
        if ( !$usuario ) {
            return response()->json(['error' => 'Ops! Usuário não encontrado!'], 403);
        }

        return response()->json(['data' => $usuario], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $usuario = $this->usuarios->find($id);
        if ( !$usuario ) {
            return response()->json(['error' => 'Ops! Usuário não encontrado!'], 403);
        }
        $validando = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'phone' => 'string',
            'photo' => 'file|max:2048|mimes:jpg,png',
            'password' => 'string|min:8'
        ]);

        if ( $validando->fails() ) {
            return response()->json(['error' => $validando->errors()]);
        }


        $dados = $request->all();
        // upload da foto do usuário
        if ( $request->file('photo') ) {
            
            // Deleta arquivo atual do servidor para upload do novo
            Storage::delete(["photos/{$usuario->photo}"]);

            // gera um novo nome ao arquivo
            $nomeArquivo = uniqid(date('HisYmd'));  
            $extensao = $request->photo->extension();
            $arquivoFinal = "{$nomeArquivo}.{$extensao}";
        
            $upload = $request->photo->storeAs('photos', $arquivoFinal);

            if ( !$upload ) {
                return response()->json(['error' => 'Ops! Houve uma falha ao realizar o upload da Foto!'], 403);
            }

            $dados['photo'] = $arquivoFinal;
        }
       
        
        if ( isset($request->password) ) {
            $dados['password'] = Hash::make($request->password);
        }

        $response = $usuario->update($dados);

        // em caso de sucesso no cadastro do usuário, sistema registra o token de acesso a api
        if ( $response ) {
            return response()->json(['success' => 'Usuário atualizado com sucesso!', 'data' => $dados], 201);
        } else {
            return response()->json(['error' => 'Ops! Houve um erro ao atualizar usuário, por favor verifique os campos informados e tente novamente!'], 403);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, Request $request)
    {
        $usuario = $this->usuarios->find($id);
        if ( !$usuario ) {
            return response()->json(['error' => 'Ops! Usuário não encontrado!'], 403);
        }

        $response = $usuario->delete();

        // em caso de sucesso no cadastro do usuário, sistema registra o token de acesso a api
        if ( $response ) {
            return response()->json(['success' => 'Usuário deletado com sucesso!'], 201);
        } else {
            return response()->json(['error' => 'Ops! Houve um erro ao deletar usuário, por favor tente novamente!'], 403);
        }
    }
}

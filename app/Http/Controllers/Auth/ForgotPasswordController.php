<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\FrontendManage\Entities\LoginPage;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    public function showLinkRequestForm()
    {
        $page = LoginPage::getData();
        return view(theme('auth.passwords.email'), compact('page'));
    }

    public function SendPasswordResetLink()
    {
        $page = LoginPage::getData();
        return view(theme('auth.passwords.email'), compact('page'));
    }

    public function ResetPassword()
    {
        $page = LoginPage::getData();
        return view(theme('auth.passwords.reset'), compact('page'));
    }

    public function checkUserIdentity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Inserisci un nome utente o un indirizzo email valido.'
            ]);
        }

        $identity = trim($request->identity);

        $user = User::where('email', $identity)
            ->orWhere('username', $identity)
            ->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'message' => 'Utente trovato! Procedi con la nuova password.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Nome utente o email non trovati.'
        ]);
    }

    public function resetPasswordDirect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'La password è obbligatoria.',
            'password.min' => 'La password deve contenere almeno 8 caratteri.',
            'password.confirmed' => 'Le password non coincidono.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utente non trovato.'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reimpostata con successo! Ora puoi effettuare il login.'
        ]);
    }
}

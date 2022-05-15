{{-- resources/views/emails/password.blade.php --}}
 
Clique aqui para redefinir sua senha: <a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a>
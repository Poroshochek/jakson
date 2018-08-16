<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const IS_BANNED = 1;
    const IS_ACTIVE = 0;

    protected $fillable = [
        'name', 'email', 'ustatus'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function add($fields)
    {
        $user = new self;
        $user->fill($fields);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->generatePassword($fields['password']);
        $this->save();
    }

    public function generatePassword($password)
    {
        if($password != null) {
            $this->password = bcrypt($password);
            $this->save();
        }
    }

    public function remove()
    {
        $this->removeAvatar();
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if($image == null) { return; }

        $this->removeAvatar();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAS('uploads', $filename);
        $this->avatar = $filename;
        $this->save();
    }

    public function removeAvatar()
    {
        if($this->avatar != null) {
            Storage::delete('uploads/' . $this->avatar);
        }
    }

    public function getAvatar()
    {
        if($this->avatar == null)
        {
            return '/img/no-img.png';
        }

        return '/uploads/' . $this->avatar;
    }

    public function makeAdmin ()
    {
        $this->is_admin = 1;
    }

    public function makeNormal()
    {
        $this->is_admin = 0;
    }

    public function toggleAdmin($val)
    {
        if ($val == null) {
            return $this->makeNormal();
        }
        return $this->makeAdmin();
    }

    public function bun()
    {
        $this->status = User::IS_BANNED;
        $this->save();
    }

    public function unbun()
    {
        $this->status = User::IS_ACTIVE;
        $this->save();
    }

    public function toggleBun()
    {
        if ($this->status == 1) {
            return $this->unbun();
        }
        return $this->bun();
    }
}

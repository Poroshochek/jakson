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
        'name', 'email', 'password',
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
        $user->password = bcrypt($fields['password']);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->password = bcrypt($fields['password']);
        $this->save();
    }

    public function remove()
    {
        Storage::delete('uploads/' . $this->image);
        $this->remove();
    }

    public function uploadAvatar($image)
    {
        if($image == null) { return; }

        Storage::delete('uploads/' . $this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getAvatar()
    {
        if($this->image = null)
        {
            return '/img/no-avatar-img.png';
        }

        return '/uploads/' . $this->image;
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

    public function toggleBun($val)
    {
        if ($val == null) {
            return $this->unbun();
        }
        return $this->bun();
    }
}

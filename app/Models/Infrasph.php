<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classph extends Model
{
        function tampil($table){
        return DB::table($table)->get();
    }

        function aksi($table, $alldata){
        return DB::table($table)->where($alldata)->get()->first();
        }

        function tambah($table, $data) {
        return DB::table($table)->insertGetId($data);
    }

        function edit($table,$where,$data){
            return DB::table($table)->where($where)->update($data);
        }

        function hapus($table,$where){
            return DB::table($table)->where($where)->delete();
        }



        public function join($table1, $table2,$table3, $on, $on1)
        {
            return DB::table($table1)
                ->leftjoin($table2, $on[0], $on[1], $on[2])
                ->leftjoin($table3, $on1[0],$on1[1], $on1[2])
                ->get();
            }

}

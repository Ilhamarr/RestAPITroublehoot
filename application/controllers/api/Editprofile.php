<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Editprofile extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Account_model');
  }

  public function index_post()
  {
    $account_id   = $this->post('account_id');
    $account      = $this->Account_model->getAccount($account_id);
    $firstname    = $this->post('firstname');
    $lastname     = $this->post('lastname');
    $email        = $this->post('email');
    $phone        = $this->post('phone');
    $address      = $this->post('address');
    $provider     = $account[0]['oauth_provider'];
    // kolom : accounts_id
    // kolom : modified_at


    if ($account_id != null) {
      // field name foto = picture
      // jika foto diedit
      if (!empty($_FILES['picture']['name'])) {
        $config['upload_path']   = './assets/image/profile';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = '2400';
        $this->upload->initialize($config);
        $field_name = "picture";

        if ($this->upload->do_upload($field_name)) {

          $upload_gambar = array('upload_data' => $this->upload->data());

          if ($provider == 'google') {

            $data = array(
              'first_name'    => $firstname,
              'last_name'     => $lastname,
              'picture'       => $upload_gambar['upload_data']['file_name'],
              'modified_at'   => date("Y-m-d H:i:s"),
              'nomor'         => $phone,
              'alamat'        => $address,
            );

            $q = $this->Account_model->updateAccount($data, $account_id);

            if ($q) {
              $this->response([
                'status'     => true,
                'message'    => "Ubah profil sukses",
                'data'       => $q
              ], REST_Controller::HTTP_OK);
            } else {
              $this->response([
                'status'     => false,
                'message'    => 'Ubah profil gagal',
              ], REST_Controller::HTTP_OK);
            }
          } else {
            if ($account->picture != null) {
              unlink('./assets/image/profile/' . $account->picture);
            }
            $data = array(
              'first_name'    => $firstname,
              'last_name'     => $lastname,
              'email'         => $email,
              'picture'       => $upload_gambar['upload_data']['file_name'],
              'modified_at'   => date("Y-m-d H:i:s"),
              'nomor'         => $phone,
              'alamat'        => $address,
            );

            $q = $this->Account_model->updateAccount($data, $account_id);

            if ($q) {
              $this->response([
                'status'     => true,
                'message'    => "Ubah profil sukses",
                'data'       => $q
              ], REST_Controller::HTTP_OK);
            } else {
              $this->response([
                'status'     => false,
                'message'    => 'Ubah profil gagal'
              ], REST_Controller::HTTP_OK);
            }
          }
        }
      }

      // jika foto tidak di edit
      else {
        if ($provider == 'google') {
          $data = array(
            'first_name'    => $firstname,
            'last_name'     => $lastname,
            'modified_at'   => date("Y-m-d H:i:s"),
            'nomor'         => $phone,
            'alamat'        => $address,
          );

          $q = $this->Account_model->updateAccount($data, $account_id);

          if ($q) {
            $this->response([
              'status'     => true,
              'message'    => "Ubah profil sukses",
              'data'       => $q
            ], REST_Controller::HTTP_OK);
          } else {
            $this->response([
              'status'     => false,
              'message'    => 'Ubah profil gagal'
            ], REST_Controller::HTTP_OK);
          }
        } else {
          $data = array(
            'first_name'    => $firstname,
            'last_name'     => $lastname,
            'email'         => $email,
            'modified_at'   => date("Y-m-d H:i:s"),
            'nomor'         => $phone,
            'alamat'        => $address,
          );

          $q = $this->Account_model->updateAccount($data, $account_id);

          if ($q) {
            $this->response([
              'status'     => true,
              'message'    => "Ubah profil sukses",
              'data'       => $q
            ], REST_Controller::HTTP_OK);
          } else {
            $this->response([
              'status'     => false,
              'message'    => 'Ubah profil gagal'
            ], REST_Controller::HTTP_OK);
          }
        }
      }
    }
  }
}
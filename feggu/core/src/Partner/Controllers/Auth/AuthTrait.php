<?php

namespace Feggu\Core\Partner\Controllers\Auth;

use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguCustomField;
use Feggu\Core\Partner\Models\FegguCountry;

/**
 * Trait Auth controller.
 */
trait AuthTrait
{
    /**
     * Map validate when edit customer
     *
     * @param   [array]  $data  [$data description]
     *
     * @return  [array]         [return description]
     */
    public function mappingValidatorEdit($data)
    {
        $dataUpdate = [
            'first_name' => $data['first_name'],
        ];
        if (isset($data['status'])) {
            $dataUpdate['status']  = $data['status'];
        }
        $validate = [
            'first_name' => config('validation.customer.first_name', 'required|string|max:100'),
            'password' => config('validation.customer.password_null', 'nullable|string|min:6'),
        ];

        if (!empty($data['password'])) {
            $dataUpdate['password'] = bcrypt($data['password']);
        }
        if (!empty($data['email'])) {
            $dataUpdate['email'] = $data['email'];
            $validate['email'] = config('validation.customer.email', 'required|string|email|max:255').'|unique:"'.FegguConsultation::class.'",email, '.$data['id'].',id';
        }
        //Dont update id
        unset($data['id']);

        if (au_config('customer_lastname')) {
            if (au_config('customer_lastname_required')) {
                $validate['last_name'] = config('validation.customer.last_name_required', 'required|string|max:100');
            } else {
                $validate['last_name'] = config('validation.customer.last_name_null', 'nullable|string|max:100');
            }
            if (!empty($data['last_name'])) {
                $dataUpdate['last_name'] = $data['last_name'];
            }
        }
        if (au_config('customer_address')) {
            if (au_config('customer_address_required')) {
                $validate['address'] = config('validation.customer.address_required', 'required|string|max:100');
            } else {
                $validate['address'] = config('validation.customer.address_null', 'nullable|string|max:100');
            }
            if (!empty($data['address'])) {
                $dataUpdate['address'] = $data['address'];
            }
        }

        if (au_config('customer_detail_address')) {
            if (au_config('customer_detail_address_required')) {
                $validate['detail_address'] = config('validation.customer.detail_address_required', 'required|string|max:100');
            } else {
                $validate['detail_address'] = config('validation.customer.detail_address_null', 'nullable|string|max:100');
            }
            if (!empty($data['detail_address'])) {
                $dataUpdate['detail_address'] = $data['detail_address'];
            }
        }

        if (au_config('customer_locality')) {
            if (au_config('customer_locality_required')) {
                $validate['locality'] = config('validation.customer.locality_required', 'required|string|max:100');
            } else {
                $validate['locality'] = config('validation.customer.locality_null', 'nullable|string|max:100');
            }
            if (!empty($data['locality'])) {
                $dataUpdate['locality'] = $data['locality'];
            }
        }


        if (au_config('customer_phone')) {
            if (au_config('customer_phone_required')) {
                $validate['phone'] = config('validation.customer.phone_required', 'regex:/^0[^0][0-9\-]{6,12}$/');
            } else {
                $validate['phone'] = config('validation.customer.phone_null', 'nullable|regex:/^0[^0][0-9\-]{6,12}$/');
            }
            if (!empty($data['phone'])) {
                $dataUpdate['phone'] = $data['phone'];
            }
        }

        if (au_config('customer_country')) {
            $arraycountry = (new FegguCountry)->pluck('code')->toArray();
            if (au_config('customer_country_required')) {
                $validate['country'] = config('validation.customer.country_required', 'required|string|min:2').'|in:'. implode(',', $arraycountry);
            } else {
                $validate['country'] = config('validation.customer.country_null', 'nullable|string|min:2').'|in:'. implode(',', $arraycountry);
            }
            if (!empty($data['country'])) {
                $dataUpdate['country'] = $data['country'];
            }
        }

        if (au_config('customer_postcode')) {
            if (au_config('customer_postcode_required')) {
                $validate['postcode'] = config('validation.customer.postcode_required', 'required|min:5');
            } else {
                $validate['postcode'] = config('validation.customer.postcode_null', 'nullable|min:5');
            }
            if (!empty($data['postcode'])) {
                $dataUpdate['postcode'] = $data['postcode'];
            }
        }

        if (au_config('customer_company')) {
            if (au_config('customer_company_required')) {
                $validate['company'] = config('validation.customer.company_required', 'required|string|max:100');
            } else {
                $validate['company'] = config('validation.customer.company_null', 'nullable|string|max:100');
            }
            if (!empty($data['company'])) {
                $dataUpdate['company'] = $data['company'];
            }
        }

        if (au_config('customer_sex')) {
            if (au_config('customer_sex_required')) {
                $validate['sex'] = config('validation.customer.sex_required', 'required|integer|max:10');
            } else {
                $validate['sex'] = config('validation.customer.sex_null', 'nullable|integer|max:10');
            }
            if (!empty($data['sex'])) {
                $dataUpdate['sex'] = $data['sex'];
            }
        }

        if (au_config('customer_birthday')) {
            if (au_config('customer_birthday_required')) {
                $validate['birthday'] = config('validation.customer.birthday_required', 'required|date|date_format:Y-m-d');
            } else {
                $validate['birthday'] = config('validation.customer.birthday_null', 'nullable|date|date_format:Y-m-d');
            }
            if (!empty($data['birthday'])) {
                $dataUpdate['birthday'] = $data['birthday'];
            }
        }

        if (au_config('customer_group')) {
            if (au_config('customer_group_required')) {
                $validate['group'] = config('validation.customer.group_required', 'required|integer|max:10');
            } else {
                $validate['group'] = config('validation.customer.group_null', 'nullable|integer|max:10');
            }
            if (!empty($data['group'])) {
                $dataUpdate['group'] = $data['group'];
            }
        }
        if (au_config('customer_blood_group')) {
            if (au_config('customer_blood_group_required')) {
                $validate['blood_group'] = config('validation.customer.blood_group_required', 'required|integer|max:10');
            } else {
                $validate['blood_group'] = config('validation.customer.blood_group_null', 'nullable|integer|max:10');
            }
            if (!empty($data['blood_group'])) {
                $dataUpdate['blood_group'] = $data['group'];
            }
        }


        $messages = [
            'last_name.required'   => au_language_render('validation.required', ['attribute'=> au_language_render('customer.last_name')]),
            'first_name.required'  => au_language_render('validation.required', ['attribute'=> au_language_render('customer.first_name')]),
            'email.required'       => au_language_render('validation.required', ['attribute'=> au_language_render('customer.email')]),
            'password.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.password')]),
            'address.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.address')]),
            'detail_address.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.detail_address')]),
            'locality.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.locality')]),
            'phone.required'       => au_language_render('validation.required', ['attribute'=> au_language_render('customer.phone')]),
            'country.required'     => au_language_render('validation.required', ['attribute'=> au_language_render('customer.country')]),
            'postcode.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.postcode')]),
            'company.required'     => au_language_render('validation.required', ['attribute'=> au_language_render('customer.company')]),
            'sex.required'         => au_language_render('validation.required', ['attribute'=> au_language_render('customer.sex')]),
            'birthday.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.birthday')]),
            'email.email'          => au_language_render('validation.email', ['attribute'=> au_language_render('customer.email')]),
            'phone.regex'          => au_language_render('customer.phone_regex'),
            'password.confirmed'   => au_language_render('validation.confirmed', ['attribute'=> au_language_render('customer.password')]),
            'postcode.min'         => au_language_render('validation.min', ['attribute'=> au_language_render('customer.postcode')]),
            'password.min'         => au_language_render('validation.min', ['attribute'=> au_language_render('customer.password')]),
            'country.min'          => au_language_render('validation.min', ['attribute'=> au_language_render('customer.country')]),
            'first_name.max'       => au_language_render('validation.max', ['attribute'=> au_language_render('customer.first_name')]),
            'email.max'            => au_language_render('validation.max', ['attribute'=> au_language_render('customer.email')]),
            'address.max'         => au_language_render('validation.max', ['attribute'=> au_language_render('customer.address')]),
            'detail_address.max'         => au_language_render('validation.max', ['attribute'=> au_language_render('customer.detail_address')]),
            'locality.max'         => au_language_render('validation.max', ['attribute'=> au_language_render('customer.locality')]),
            'last_name.max'        => au_language_render('validation.max', ['attribute'=> au_language_render('customer.last_name')]),
            'birthday.date'        => au_language_render('validation.date', ['attribute'=> au_language_render('customer.birthday')]),
            'birthday.date_format' => au_language_render('validation.date_format', ['attribute'=> au_language_render('customer.birthday')]),
        ];
        $dataMap = [
            'validate' => $validate,
            'messages' => $messages,
            'dataUpdate' => $dataUpdate
        ];
        return $dataMap;
    }

    /**
     * Mapp validate when register new customer
     *
     * @param [array] $data  [$data description]
     *
     * @return [array]         [return description]
     */
    public function mappingValidator($data)
    {
        $dataInsert = $this->mappDataInsert($data);
        $validate = [
            'first_name' => config('validation.customer.first_name', 'required|string|max:100'),
            'email' => config('validation.customer.email', 'required|string|email|max:255').'|unique:"'.FegguConsultation::class.'",email',
            'password' => config('validation.customer.password', 'nullable|string|min:6'),
        ];

        //Custom fields
        $customFields = (new FegguCustomField)->getCustomField($type = 'customer');
        if ($customFields) {
            foreach ($customFields as $field) {
                if ($field->required) {
                    $validate['fields.'.$field->code] = 'required';
                }
            }
        }

        if (au_config('customer_lastname')) {
            if (au_config('customer_lastname_required')) {
                $validate['last_name'] = config('validation.customer.last_name_required', 'required|string|max:100');
            } else {
                $validate['last_name'] = config('validation.customer.last_name_null', 'nullable|string|max:100');
            }
        }
        if (au_config('customer_address')) {
            if (au_config('customer_address_required')) {
                $validate['address'] = config('validation.customer.address_required', 'required|string|max:100');
            } else {
                $validate['address'] = config('validation.customer.address_null', 'nullable|string|max:100');
            }
        }

        if (au_config('customer_detail_address')) {
            if (au_config('customer_detail_address_required')) {
                $validate['detail_address'] = config('validation.customer.detail_address_required', 'required|string|max:100');
            } else {
                $validate['detail_address'] = config('validation.customer.detail_address_null', 'nullable|string|max:100');
            }
        }

        if (au_config('customer_locality')) {
            if (au_config('customer_locality_required')) {
                $validate['locality'] = config('validation.customer.locality_required', 'required|string|max:100');
            } else {
                $validate['locality'] = config('validation.customer.locality_null', 'nullable|string|max:100');
            }
        }


        if (au_config('customer_phone')) {
            if (au_config('customer_phone_required')) {
                $validate['phone'] = config('validation.customer.phone_required', 'regex:/^0[^0][0-9\-]{6,12}$/');
            } else {
                $validate['phone'] = config('validation.customer.phone_null', 'nullable|regex:/^0[^0][0-9\-]{6,12}$/');
            }
        }
        if (au_config('customer_country')) {
            $arraycountry = (new FegguCountry)->pluck('code')->toArray();
            if (au_config('customer_country_required')) {
                $validate['country'] = config('validation.customer.country_required', 'required|string|min:2').'|in:'. implode(',', $arraycountry);
            } else {
                $validate['country'] = config('validation.customer.country_null', 'nullable|string|min:2').'|in:'. implode(',', $arraycountry);
            }
        }

        if (au_config('customer_postcode')) {
            if (au_config('customer_postcode_required')) {
                $validate['postcode'] = config('validation.customer.postcode_required', 'required|min:5');
            } else {
                $validate['postcode'] = config('validation.customer.postcode_null', 'nullable|min:5');
            }
        }
        if (au_config('customer_company')) {
            if (au_config('customer_company_required')) {
                $validate['company'] = config('validation.customer.company_required', 'required|string|max:100');
            } else {
                $validate['company'] = config('validation.customer.company_null', 'nullable|string|max:100');
            }
        }
        if (au_config('customer_sex')) {
            if (au_config('customer_sex_required')) {
                $validate['sex'] = config('validation.customer.sex_required', 'required|integer|max:10');
            } else {
                $validate['sex'] = config('validation.customer.sex_null', 'nullable|integer|max:10');
            }
        }
        if (au_config('customer_birthday')) {
            if (au_config('customer_birthday_required')) {
                $validate['birthday'] = config('validation.customer.birthday_required', 'required|date|date_format:Y-m-d');
            } else {
                $validate['birthday'] = config('validation.customer.birthday_null', 'nullable|date|date_format:Y-m-d');
            }
        }
        if (au_config('customer_group')) {
            if (au_config('customer_group_required')) {
                $validate['group'] = config('validation.customer.group_required', 'required|integer|max:10');
            } else {
                $validate['group'] = config('validation.customer.group_null', 'nullable|integer|max:10');
            }
        }

        if (au_config('customer_blood_group')) {
            if (au_config('customer_blood_group_required')) {
                $validate['blood_group'] = config('validation.customer.blood_group_required', 'required|integer|max:10');
            } else {
                $validate['blood_group'] = config('validation.customer.blood_group_null', 'nullable|integer|max:10');
            }
        }



        $messages = [
            'last_name.required'   => au_language_render('validation.required', ['attribute'=> au_language_render('customer.last_name')]),
            'first_name.required'  => au_language_render('validation.required', ['attribute'=> au_language_render('customer.first_name')]),
            'email.required'       => au_language_render('validation.required', ['attribute'=> au_language_render('customer.email')]),
            'password.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.password')]),
            'address.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.address')]),
            'detail_address.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.detail_address')]),
            'locality.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.locality')]),
            'phone.required'       => au_language_render('validation.required', ['attribute'=> au_language_render('customer.phone')]),
            'country.required'     => au_language_render('validation.required', ['attribute'=> au_language_render('customer.country')]),
            'postcode.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.postcode')]),
            'company.required'     => au_language_render('validation.required', ['attribute'=> au_language_render('customer.company')]),
            'sex.required'         => au_language_render('validation.required', ['attribute'=> au_language_render('customer.sex')]),
            'birthday.required'    => au_language_render('validation.required', ['attribute'=> au_language_render('customer.birthday')]),
            'email.email'          => au_language_render('validation.email', ['attribute'=> au_language_render('customer.email')]),
            'phone.regex'          => au_language_render('customer.phone_regex'),
            'password.confirmed'   => au_language_render('validation.confirmed', ['attribute'=> au_language_render('customer.password')]),
            'postcode.min'         => au_language_render('validation.min', ['attribute'=> au_language_render('customer.postcode')]),
            'password.min'         => au_language_render('validation.min', ['attribute'=> au_language_render('customer.password')]),
            'country.min'          => au_language_render('validation.min', ['attribute'=> au_language_render('customer.country')]),
            'first_name.max'       => au_language_render('validation.max', ['attribute'=> au_language_render('customer.first_name')]),
            'email.max'            => au_language_render('validation.max', ['attribute'=> au_language_render('customer.email')]),
            'address.max'         => au_language_render('validation.max', ['attribute'=> au_language_render('customer.address')]),
            'detail_address.max'         => au_language_render('validation.max', ['attribute'=> au_language_render('customer.detail_address')]),
            'locality.max'         => au_language_render('validation.max', ['attribute'=> au_language_render('customer.locality')]),
            'last_name.max'        => au_language_render('validation.max', ['attribute'=> au_language_render('customer.last_name')]),
            'birthday.date'        => au_language_render('validation.date', ['attribute'=> au_language_render('customer.birthday')]),
            'birthday.date_format' => au_language_render('validation.date_format', ['attribute'=> au_language_render('customer.birthday')]),
        ];
        $dataMap = [
            'validate' => $validate,
            'messages' => $messages,
            'dataInsert' => $dataInsert
        ];
        return $dataMap;
    }

    /**
     * Mapping data before inser
     *
     * @param [type]  $data  [$data description]
     *
     * @return [type]         [return description]
     */
    public function mappDataInsert($data)
    {
        $dataInsert = [
            'first_name' => $data['first_name'] ?? '',
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ];
        if (isset($data['status'])) {
            $dataInsert['status']  = $data['status'];
        }
        if (au_config('customer_lastname')) {
            if (!empty($data['last_name'])) {
                $dataInsert['last_name'] = $data['last_name'];
            }
        }
        if (au_config('customer_firstname_kana')) {
            if (!empty($data['first_name_kana'])) {
                $dataInsert['first_name_kana'] = $data['first_name_kana'];
            }
        }
        if (au_config('customer_lastname_kana')) {
            if (!empty($data['last_name_kana'])) {
                $dataInsert['last_name_kana'] = $data['last_name_kana'];
            }
        }
        if (au_config('customer_address')) {
            if (!empty($data['address'])) {
                $dataInsert['address'] = $data['address'];
            }
        }
        if (au_config('customer_detail_address')) {
            if (!empty($data['detail_address'])) {
                $dataInsert['detail_address'] = $data['detail_address'];
            }
        }

        if (au_config('customer_locality')) {
            if (!empty($data['locality'])) {
                $dataInsert['locality'] = $data['locality'];
            }
        }

        if (au_config('customer_phone')) {
            if (!empty($data['phone'])) {
                $dataInsert['phone'] = $data['phone'];
            }
        }
        if (au_config('customer_country')) {
            if (!empty($data['country'])) {
                $dataInsert['country'] = $data['country'];
            }
        }
        if (au_config('customer_postcode')) {
            if (!empty($data['postcode'])) {
                $dataInsert['postcode'] = $data['postcode'];
            }
        }
        if (au_config('customer_company')) {
            if (!empty($data['company'])) {
                $dataInsert['company'] = $data['company'];
            }
        }
        if (au_config('customer_sex')) {
            if (!empty($data['sex'])) {
                $dataInsert['sex'] = $data['sex'];
            }
        }
        if (au_config('customer_birthday')) {
            if (!empty($data['birthday'])) {
                $dataInsert['birthday'] = $data['birthday'];
            }
        }
        if (au_config('customer_group')) {
            if (!empty($data['group'])) {
                $dataInsert['group'] = $data['group'];
            }
        }

        if (au_config('customer_blood_group')) {
            if (!empty($data['blood_group'])) {
                $dataInsert['blood_group'] = $data['blood_group'];
            }
        }
        return $dataInsert;
    }
}

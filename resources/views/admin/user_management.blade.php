@extends('admin.master')
@section('content')
@section('title', 'User Management')
<!-- {{session('role')}} -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>User Management</h2>
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <button class="btn-theme"  data-bs-toggle="modal" data-bs-target="#myModal">+ Add New</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="table-responsive">
                <table
                        id="example"
                        class="table data-table"
                        style="width: 100%"
                >
                    <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Email Address</th>
                        <th >Phone Number</th>
                        <th class="text-start">User Type</th>
                        <th id="action">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        
                        @if(count($records))
                            @foreach($records['data'] AS $item)
                        <tr>
                            <td>{{$item['name']}}</td>
                            <td>{{$item['email']}}</td>
                            <td >{{$item['mobile_no']}}</td>
                            <td class="text-start">{{$item['user_type']}}</td>
                            <td>
                                <div class="dropdown">
                                    <button
                                            class="btn btn-secondary dropdown-toggle"
                                            type="button"
                                            id="actionMenu1"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                    >
                                        Actions
                                    </button>
                                    <ul
                                            class="dropdown-menu"
                                            aria-labelledby="actionMenu1"
                                    >
                                        <li>
                                            <a
                                                    class="dropdown-item"
                                                    href="#"
                                                    onclick="editRow({{$item['id']}})"
                                            >Edit</a
                                            >
                                        </li>
                                        <li>
                                            <a
                                                    class="dropdown-item"
                                                    href="#"
                                                    onclick="deleteRow({{$item['id']}})"
                                            >Delete</a
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                            @endforeach
                        @endif
                    <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- Add Modal -->
<div class="modal fade " id="myModal"  tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered project-modal">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                    </button>
                </div>
                <form id="add_form" action="{{URL::to('admin/user_management/store')}}" method="POST" class="needs-validation" novalidate>
                    {{csrf_field()}}

                    <div class="modal-body companyinfobody rm-companyinfobody-modified ">
                    <div class="row">
                        <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                            <input type="text" name="name" placeholder="Enter Name" 
                                class="form-control place-color" 
                                required 
                                pattern="[A-Za-z\s]{2,}"
                                autocomplete="off">
                            <div class="invalid-feedback">
                                Please enter a valid name (at least 2 characters, letters only)
                            </div>
                        </div>
                        <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                            <input type="email" name="email" 
                                placeholder="Enter Email" 
                                class="form-control place-color" 
                                required
                                autocomplete="off">
                            <div class="invalid-feedback">
                                Please enter a valid email address
                            </div>
                        </div>
                        <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                            <label class="validate-pwd position-relative">
                                <input type="password" name="password" 
                                    id="password"
                                    placeholder="Enter Password" 
                                    class="form-control place-color" 
                                    required
                                    autocomplete="off">
                                <i class="bi bi-eye-slash position-absolute end-0 top-0 mt-2 me-2" 
                                id="togglePassword" 
                                style="cursor: pointer;"></i>
                                <div class="invalid-feedback" id="passwordError">
                                    Password must be at least 8 characters, contain one uppercase, one lowercase, one number, and one special character
                                </div>
                            </label>
                        </div>
                        <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                            <label class="validate-pwd position-relative">
                                <input type="password" name="password_confirmation" 
                                    id="password_confirmation"
                                    placeholder="Confirm Password" 
                                    class="form-control place-color" 
                                    required
                                    autocomplete="off">
                                <i class="bi bi-eye-slash position-absolute end-0 top-0 mt-2 me-2" 
                                id="toggleConfirmPassword" 
                                style="cursor: pointer;"></i>
                                <div class="invalid-feedback">
                                    Passwords do not match
                                </div>
                            </label>
                        </div>
                        <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                            <label class="validate-pwd position-relative custom-field-num">
                                <input type="tel" name="mobile_no"
                                    id="mobile_no"
                                    placeholder="Enter Mobile Number" 
                                    class="form-control place-color" 
                                    required
                                    autocomplete="off">
                                <!-- <span class="placeholder">Mobile Number</span> -->
                                <div class="invalid-feedback">
                                    Please enter a valid mobile number
                                </div>
                            </label>
                        </div>
                        <div class="col-md-12 companyinfobody rm-companyinfobody-select-modified">
                            <select name="company_group_id" 
                                    id="sel1" 
                                    class="form-select add-select" 
                                    required>
                                <option value="">Select User Type</option>
                                @foreach ($CompanyUsers['companyGroup'] as $company)
                                    <option value="{{$company['id']}}">{{$company['title']}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a user type
                            </div>
                        </div>
                            <div class="col-md-12 companyinfobody rm-companyinfobody-modified iu_mgt_special">
                                <!-- <label for="card-element">Credit Card Details</label> -->
                                <div id="card-element">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <!-- Used to display form errors. -->
                                <div id="card-errors" role="alert"></div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-save">Save </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
<!-- Edit Modal -->
<div class="modal fade new-modal" id="editModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered project-modal">

        <!-- Modal content-->
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Inspector User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                    </button>
                </div>
            <form id="update_form" action="{{URL::to('admin/user_management/update')}}" method="POST">
                {{csrf_field()}}
                <input type="hidden" name="page" value="{{\Request::input('page',1)}}">
                <div class="modal-body companyinfobody rm-companyinfobody-modified">
                    <div class="fcol-md-12 companyinfobody rm-companyinfobody-modified">
                        <input type="text" name="name" class="form-control place-color"
                                placeholder="Enter Name">
                    </div>
                    <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                        <input type="text" name="email"  class="form-control place-color"
                                placeholder="Enter Email">
                    </div>
                    <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                        <label class="validate-pwd position-relative">
                            <input type="password" name="password"
                                   id="edit_password"
                                   placeholder="Enter New Password" 
                                   class="form-control place-color"
                                   aria-describedby="emailHelp"
                                   autocomplete="off">
                            <i class="bi bi-eye-slash position-absolute end-0 top-0 mt-2 me-2" 
                               id="toggleEditPassword" 
                               style="cursor: pointer;"></i>
                            <div class="invalid-feedback" id="editPasswordError">
                                Password must be at least 8 characters, contain one uppercase, one lowercase, one number, and one special character
                            </div>
                        </label>
                    </div>
                    <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                        <input type="text" name="mobile_no" class="form-control place-color"
                                placeholder="Enter Mobile Number">
                    </div>
                    <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                        <select name="company_group_id"  id="sel2" class="form-select add-select">
                            <option value="">User type</option>
                            @foreach ($CompanyUsers['companyGroup'] as $company)
                                <option value="{{$company['id']}}">{{$company['title']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-save">Update </button>
                </div>
            </form>
        </div>

    </div>
</div>    
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" id="confirmDeleteButton" class="btn btn-save">Delete </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push("page_css")
    <style>
        .validate-pwd input:focus + .placeholder.mobile_no{
            opacity: 1;
        }
        .validate-pwd {
            position: relative;
        }

        .validate-pwd input:focus {
            box-shadow: none; /* Remove shadow effect on focus */
        }

        label.validate-pwd {
            transition: all 0.2s ease; /* Smooth transition for label */
            width:100%;
        }

        .validate-pwd input:focus + .placeholder,
        .validate-pwd input:not(:placeholder-shown) + .placeholder {
            top: -10px;
            font-size: 12px;
            color: #007bff; /* Change to your desired color */
        }

        .placeholder {
            position: absolute;
            top: 10px;
            left: 10px;
            transition: all 0.2s ease;
            color: #aaa;
        }
        .custom-field-num {
            position: relative;
            width: 100%;
        }

        .custom-field-num input {
            /* padding-top: 1.5rem; */
            padding-bottom: 0.5rem;
        }

        .custom-field-num .placeholder {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            transition: all 0.2s ease;
            pointer-events: none;
        }

        .custom-field-num input:focus + .placeholder,
        .custom-field-num input.dirty + .placeholder {
            top: 0.5rem;
            font-size: 0.75rem;
            color: #007bff;
        }
    </style>
@endpush
@push("page_js")
<script>
// Add this to your existing JavaScript section
document.addEventListener("DOMContentLoaded", function() {
    const input = document.querySelector('#mobile_no');
    const label = input.closest('.custom-field-num');
    const placeholderElement = label.querySelector('.placeholder');

    // Restrict input to numbers only and format
    input.addEventListener('input', function(e) {
        // Remove non-digit characters except the initial country code
        let value = this.value.replace(/[^\d+]/g, '');
        
        // Preserve country code if present
        const countryCodeMatch = value.match(/^\+\d{1,4}/);
        const countryCode = countryCodeMatch ? countryCodeMatch[0] : '';
        
        // Get remaining digits
        let numbers = value.replace(countryCode, '');
        
        // Limit numbers based on typical phone length (adjust as needed)
        numbers = numbers.slice(0, 15 - countryCode.length);
        
        this.value = countryCode + numbers;
        
        // Add dirty class for styling
        if (this.value) {
            this.classList.add('dirty');
        } else {
            this.classList.remove('dirty');
        }
    });

    // Function to set placeholder based on country code
    function setPhonePlaceholder(countryCode) {
        const phoneFormats = {
            'AF': '+93 XX XXX XXXX',      // Afghanistan
            'AL': '+355 XXX XXX XXX',     // Albania
            'DZ': '+213 XXX XX XX XX',    // Algeria
            'AS': '+1 (684) XXX XXXX',    // American Samoa
            'AD': '+376 XXX XXX',         // Andorra
            'AO': '+244 XXX XXX XXX',     // Angola
            'AR': '+54 (XXX) XXX-XXXX',   // Argentina
            'AM': '+374 (XX) XXX XXX',    // Armenia
            'AU': '+61 X XXXX XXXX',      // Australia
            'AT': '+43 XXXX XXXXXXX',     // Austria
            'AZ': '+994 XX XXX XX XX',    // Azerbaijan
            'BH': '+973 XXXX XXXX',       // Bahrain
            'BD': '+880 XXX XXX XXX',     // Bangladesh
            'BY': '+375 (XX) XXX XX XX',  // Belarus
            'BE': '+32 XXX XX XX XX',     // Belgium
            'BZ': '+501 XXX XXXX',        // Belize
            'BJ': '+229 XX XX XXXX',      // Benin
            'BT': '+975 XXXX XXXXX',      // Bhutan
            'BO': '+591 (X) XXX XXXX',    // Bolivia
            'BA': '+387 XX XXX XXX',      // Bosnia and Herzegovina
            'BW': '+267 XX XXX XXX',      // Botswana
            'BR': '+55 (XX) XXXXX-XXXX',  // Brazil
            'BN': '+673 XXX XXXX',        // Brunei
            'BG': '+359 XXX XXX XXX',     // Bulgaria
            'BF': '+226 XX XX XX XX',     // Burkina Faso
            'KH': '+855 XXX XXX XXX',     // Cambodia
            'CM': '+237 XXXX XXXX',       // Cameroon
            'CA': '+1 (XXX) XXX-XXXX',    // Canada
            'CV': '+238 XXX XX XX',       // Cape Verde
            'TD': '+235 XX XX XX XX',     // Chad
            'CL': '+56 X XXXX XXXX',      // Chile
            'CN': '+86 (XXX) XXXX XXXX',  // China
            'CO': '+57 (XXX) XXX XXXX',   // Colombia
            'KM': '+269 XXX XXXX',        // Comoros
            'CG': '+242 XX XXX XXXX',     // Congo
            'CR': '+506 XXXX XXXX',       // Costa Rica
            'HR': '+385 XX XXX XXXX',     // Croatia
            'CU': '+53 X XXX XXXX',       // Cuba
            'CY': '+357 XX XXX XXX',      // Cyprus
            'CZ': '+420 XXX XXX XXX',     // Czech Republic
            'DK': '+45 XX XX XX XX',      // Denmark
            'DJ': '+253 XX XX XX XX',     // Djibouti
            'DO': '+1 (809) XXX-XXXX',    // Dominican Republic
            'EC': '+593 (X) XXX XXXX',    // Ecuador
            'EG': '+20 (X) XXXX XXXX',    // Egypt
            'SV': '+503 XXXX XXXX',       // El Salvador
            'EE': '+372 XXXX XXXX',       // Estonia
            'ET': '+251 XXX XXX XXX',     // Ethiopia
            'FI': '+358 XXXX XXXXX',      // Finland
            'FR': '+33 X XX XX XX XX',    // France
            'GE': '+995 (XX) XXX XXXX',   // Georgia
            'DE': '+49 XXXX XXXXXX',      // Germany
            'GH': '+233 (XX) XXX XXXX',   // Ghana
            'GR': '+30 XXX XXX XXXX',     // Greece
            'GT': '+502 XXXX XXXX',       // Guatemala
            'GN': '+224 XX XXX XXX',      // Guinea
            'HT': '+509 XXXX XXXX',       // Haiti
            'HN': '+504 XXXX XXXX',       // Honduras
            'HK': '+852 XXXX XXXX',       // Hong Kong
            'HU': '+36 XX XXX XXXX',      // Hungary
            'IS': '+354 XXX XXXX',        // Iceland
            'IN': '+91 XXXXX XXXXX',      // India
            'ID': '+62 (XX) XXXX XXXX',   // Indonesia
            'IR': '+98 (XXX) XXX XXXX',   // Iran
            'IQ': '+964 (XXX) XXX XXXX',  // Iraq
            'IE': '+353 (XX) XXX XXXX',   // Ireland
            'IL': '+972 (X) XXX XXXX',    // Israel
            'IT': '+39 XXX XXXXXXX',      // Italy
            'JM': '+1 (876) XXX-XXXX',    // Jamaica
            'JP': '+81 (XX) XXXX XXXX',   // Japan
            'JO': '+962 X XXXX XXXX',     // Jordan
            'KZ': '+7 (XXX) XXX-XX-XX',   // Kazakhstan
            'KE': '+254 XXX XXX XXX',     // Kenya
            'KR': '+82 XX XXXX XXXX',     // South Korea
            'KW': '+965 XXXX XXXX',       // Kuwait
            'LA': '+856 XX XXX XXX',      // Laos
            'LV': '+371 XXXX XXXXX',      // Latvia
            'LB': '+961 XX XXX XXX',      // Lebanon
            'LY': '+218 XX XXX XXXX',     // Libya
            'LT': '+370 (XX) XXX XXXX',   // Lithuania
            'LU': '+352 XXXX XXXX',       // Luxembourg
            'MO': '+853 XXXX XXXX',       // Macau
            'MK': '+389 XX XXX XXX',      // North Macedonia
            'MY': '+60 XX-XXX XXXX',      // Malaysia
            'MV': '+960 XXX XXXX',        // Maldives
            'ML': '+223 XX XX XXXX',      // Mali
            'MX': '+52 (XXX) XXX XXXX',   // Mexico
            'MA': '+212 (XXX) XX XX XX',  // Morocco
            'NP': '+977 XX XXX XXX',      // Nepal
            'NL': '+31 XX XXX XXXX',      // Netherlands
            'NZ': '+64 XXX XXX XXX',      // New Zealand
            'NG': '+234 XXX XXX XXXX',    // Nigeria
            'NO': '+47 XXX XX XXX',       // Norway
            'PK': '+92 XXX XXXXXXX',      // Pakistan
            'PE': '+51 XXX XXX XXX',      // Peru
            'PH': '+63 (XXX) XXX XXXX',   // Philippines
            'PL': '+48 XXX XXX XXX',      // Poland
            'PT': '+351 XXX XXX XXX',     // Portugal
            'QA': '+974 XXXX XXXX',       // Qatar
            'RO': '+40 XXX XXX XXX',      // Romania
            'RU': '+7 (XXX) XXX-XX-XX',   // Russia
            'SA': '+966 (X) XXX XXXX',    // Saudi Arabia
            'SG': '+65 XXXX XXXX',        // Singapore
            'ZA': '+27 XX XXX XXXX',      // South Africa
            'ES': '+34 XXX XXX XXX',      // Spain
            'SE': '+46 XX XXX XX XX',     // Sweden
            'CH': '+41 XX XXX XX XX',     // Switzerland
            'TH': '+66 X XXXX XXXX',      // Thailand
            'TR': '+90 (XXX) XXX XXXX',   // Turkey
            'UA': '+380 (XX) XXX XX XX',  // Ukraine
            'AE': '+971 X XXX XXXX',      // United Arab Emirates
            'GB': '+44 XXXX XXXXXX',      // United Kingdom
            'US': '+1 (XXX) XXX-XXXX',    // United States
            'UY': '+598 XXX XXXX',        // Uruguay
            'UZ': '+998 XX XXX XXXX',     // Uzbekistan
            'VE': '+58 XXX XXX XXXX',     // Venezuela
            'VN': '+84 XX XXXX XXXX',     // Vietnam
            'ZM': '+260 XXX XXX XXX',     // Zambia
            'ZW': '+263 XXX XXX XXX',     // Zimbabwe
            // Add more if needed or use libraries like libphonenumber for better validation
        };
        
        const phoneFormatsCode = {
            'AF': '+93',      // Afghanistan
            'AL': '+355',     // Albania
            'DZ': '+213',    // Algeria
            'AS': '+1 (684)',    // American Samoa
            'AD': '+376',         // Andorra
            'AO': '+244',     // Angola
            'AR': '+54',   // Argentina
            'AM': '+374',    // Armenia
            'AU': '+61',      // Australia
            'AT': '+43',     // Austria
            'AZ': '+994',    // Azerbaijan
            'BH': '+973',       // Bahrain
            'BD': '+880',     // Bangladesh
            'BY': '+375',  // Belarus
            'BE': '+32',     // Belgium
            'BZ': '+501',        // Belize
            'BJ': '+229',      // Benin
            'BT': '+975',      // Bhutan
            'BO': '+591',    // Bolivia
            'BA': '+387',      // Bosnia and Herzegovina
            'BW': '+267',      // Botswana
            'BR': '+55',  // Brazil
            'BN': '+673',        // Brunei
            'BG': '+359',     // Bulgaria
            'BF': '+226',     // Burkina Faso
            'KH': '+855',     // Cambodia
            'CM': '+237',       // Cameroon
            'CA': '+1',    // Canada
            'CV': '+238',       // Cape Verde
            'TD': '+235',     // Chad
            'CL': '+56',      // Chile
            'CN': '+86',  // China
            'CO': '+57',   // Colombia
            'KM': '+269',        // Comoros
            'CG': '+242',     // Congo
            'CR': '+506',       // Costa Rica
            'HR': '+385',     // Croatia
            'CU': '+53',       // Cuba
            'CY': '+357',      // Cyprus
            'CZ': '+420',     // Czech Republic
            'DK': '+45',      // Denmark
            'DJ': '+253',     // Djibouti
            'DO': '+1 (809)',    // Dominican Republic
            'EC': '+593',    // Ecuador
            'EG': '+20',    // Egypt
            'SV': '+503',       // El Salvador
            'EE': '+372',       // Estonia
            'ET': '+251',     // Ethiopia
            'FI': '+358',      // Finland
            'FR': '+33',    // France
            'GE': '+995',   // Georgia
            'DE': '+49',      // Germany
            'GH': '+233',   // Ghana
            'GR': '+30',     // Greece
            'GT': '+502',       // Guatemala
            'GN': '+224',      // Guinea
            'HT': '+509',       // Haiti
            'HN': '+504',       // Honduras
            'HK': '+852',       // Hong Kong
            'HU': '+36',      // Hungary
            'IS': '+354',        // Iceland
            'IN': '+91',      // India
            'ID': '+62',   // Indonesia
            'IR': '+98',   // Iran
            'IQ': '+964',  // Iraq
            'IE': '+353',   // Ireland
            'IL': '+972',    // Israel
            'IT': '+39',      // Italy
            'JM': '+1 (876)',    // Jamaica
            'JP': '+81',   // Japan
            'JO': '+962',     // Jordan
            'KZ': '+7',   // Kazakhstan
            'KE': '+254',     // Kenya
            'KR': '+82',     // South Korea
            'KW': '+965',       // Kuwait
            'LA': '+856',      // Laos
            'LV': '+371',      // Latvia
            'LB': '+961',      // Lebanon
            'LY': '+218',     // Libya
            'LT': '+370',   // Lithuania
            'LU': '+352',       // Luxembourg
            'MO': '+853',       // Macau
            'MK': '+389',      // North Macedonia
            'MY': '+60',      // Malaysia
            'MV': '+960',        // Maldives
            'ML': '+223',      // Mali
            'MX': '+52',   // Mexico
            'MA': '+212',  // Morocco
            'NP': '+977',      // Nepal
            'NL': '+31',      // Netherlands
            'NZ': '+64',      // New Zealand
            'NG': '+234',    // Nigeria
            'NO': '+47',       // Norway
            'PK': '+92',      // Pakistan
            'PE': '+51',      // Peru
            'PH': '+63',   // Philippines
            'PL': '+48',      // Poland
            'PT': '+351',     // Portugal
            'QA': '+974',       // Qatar
            'RO': '+40',      // Romania
            'RU': '+7',   // Russia
            'SA': '+966',    // Saudi Arabia
            'SG': '+65',        // Singapore
            'ZA': '+27',      // South Africa
            'ES': '+34',      // Spain
            'SE': '+46',     // Sweden
            'CH': '+41',     // Switzerland
            'TH': '+66',      // Thailand
            'TR': '+90',   // Turkey
            'UA': '+380',  // Ukraine
            'AE': '+971',      // United Arab Emirates
            'GB': '+44',      // United Kingdom
            'US': '+1',    // United States
            'UY': '+598',        // Uruguay
            'UZ': '+998',     // Uzbekistan
            'VE': '+58',     // Venezuela
            'VN': '+84',     // Vietnam
            'ZM': '+260',     // Zambia
            'ZW': '+263',     // Zimbabwe
            // Add more if needed or use libraries like libphonenumber for better validation
        }

        const placeholder = phoneFormats[countryCode] || '+XX XXXXX XXXXX';
        const code = phoneFormatsCode[countryCode] || '+XX';
        
        input.placeholder = placeholder;
        if (!input.value) {
            input.value = code;
        }
    }

    // Fetch country code using IP info API
    fetch('https://ipinfo.io/json?token=8091eed19a06a0')
        .then(response => response.json())
        .then(data => {
            const countryCode = data.country;
            setPhonePlaceholder(countryCode);
        })
        .catch(error => {
            console.error('Error fetching country code:', error);
            setPhonePlaceholder('US'); // Default to US as fallback
        });

    // Blur event for styling
    input.addEventListener('blur', (e) => {
        if (e.target.value) {
            e.target.classList.add('dirty');
        } else {
            e.target.classList.remove('dirty');
        }
    });
});
</script>
    <script>
        (function () {
            'use strict'

            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    const passwordInput = document.getElementById('password');
                    const confirmPasswordInput = document.getElementById('password_confirmation');
                    const passwordError = document.getElementById('passwordError');
                    const emailInput = form.querySelector('input[name="email"]');
                    const mobileInput = form.querySelector('input[name="mobile_no"]');
                    
                    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const mobilePattern = /^\+\d{8,15}$/;
                    // if (!mobilePattern.test(mobileInput.value)) {
                    //     mobileInput.classList.add('is-invalid');
                    //     isValid = false;
                    // } else {
                    //     mobileInput.classList.remove('is-invalid');
                    // }

                    let isValid = true;

                    // Password validation
                    if (!passwordPattern.test(passwordInput.value)) {
                        passwordInput.classList.add('is-invalid');
                        passwordError.style.display = 'block';
                        isValid = false;
                    } else {
                        passwordInput.classList.remove('is-invalid');
                        passwordError.style.display = 'none';
                    }

                    // Password confirmation
                    if (passwordInput.value !== confirmPasswordInput.value) {
                        confirmPasswordInput.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        confirmPasswordInput.classList.remove('is-invalid');
                    }

                    // Email validation
                    if (!emailPattern.test(emailInput.value)) {
                        emailInput.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        emailInput.classList.remove('is-invalid');
                    }

                    // Mobile validation
                    if (!mobilePattern.test(mobileInput.value)) {
                        mobileInput.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        mobileInput.classList.remove('is-invalid');
                    }

                    if (!form.checkValidity() || !isValid) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
        })();
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
            var passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            }
        });
        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            var confirmPasswordInput = document.getElementById('password_confirmation');
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            } else {
                confirmPasswordInput.type = 'password';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            }
        });
        document.getElementById('toggleEditPassword').addEventListener('click', function () {
            var passwordInput = document.getElementById('edit_password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            }
        });
        var updateUrl = "{{URL::to('admin/user_management/update')}}";
        $(document).ready(function () {
           var table = $('#example').DataTable({
                paging: true,
                searching: false,
                ordering: true,
                info: true,
                "language": {
                    "info": "Page _PAGE_ of _PAGES_",
                }
            })
        })

        // Edit Row
        function editRow(id) {
            
            var $editModal = $('#editModal');
                $.ajax({
                    url: "{{URL::to('admin/user_management/editFormDetails')}}/" + id,
                    method: "GET",
                    data: '',
                    success: function (response) {
                        var data = response.data;
                        console.log('response',response.data);
                        $('#update_form').attr('action', updateUrl + '/' + response.data.id);

                        $('#update_form input[name="name"]').val(response.data.first_name+' '+response.data.last_name);
                        $('#update_form input[name="email"]').val(response.data.email);
                        $('#update_form input[name="mobile_no"]').val(response.data.mobile_no);
                        $('#update_form input[name="password"]').val('');
                        // console.log('group id',response.data.company_group_id);

                        $('#update_form select[name="company_group_id"] option').each(function (key, item) {
                            if (parseInt($(item).attr('value')) == parseInt(response.data.company_group_id)) {
                                $(item).attr('selected', true);
                                console.log('selected')
                            }
                        });
                        $('#update_form select[name="company_group_id[]"]').trigger('change');
                        $editModal.modal('show');
                    },
                    error: function () {
                        alert("No Network");
                    }
                });
            // Implement edit functionality here
        }

        // Delete Row
        var deleteId; // Store the id to be deleted

        // Delete Row
        function deleteRow(id) {
            deleteId = id; // Set the id for the deletion
            $('#deleteConfirmationModal').modal('show'); // Show the modal
        }

        // When the confirm delete button is clicked
        $('#confirmDeleteButton').on('click', function() {
            $.ajax({
                url: '{!! url('admin/user_management/delete') !!}/' + deleteId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Add CSRF token to the headers
                },
                dataType: 'JSON',
                success: function(response) {
                    window.location.reload(); // Reload the page upon successful deletion
                },
                error: function() {
                    // Handle error if needed
                }
            });

            $('#deleteConfirmationModal').modal('hide'); // Hide the modal after deletion
        });
        $(document).ready(function () {
            var width = $(window).width()
            $(window).resize(function (e) {
                e.preventDefault()
                width = $(window).width()
                if (width <= 767) {
                    // Compare with a number
                    $('#wrapper').removeClass('toggled')
                }
            })
            $('#menu-toggle').click(function (e) {
                e.preventDefault()
                $('#wrapper').toggleClass('toggled')
            })
        })
    </script>
<script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        // Create a Stripe client.
        var stripe = Stripe('{{config('app.stripe_pub_key')}}');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('add_form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('add_form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>    
@endpush
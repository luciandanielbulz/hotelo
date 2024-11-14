<x-layout>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    <div class="container">
        <div class="row">
            <div class = "col">
                <h3>Neuen Kunden anlegen</h3>
            </div>

            <div class = "col">
                <a href="{{ route('customer.index') }}" class="btn btn-transparent my-1 float-right">Zurück</a>
            </div>
        </div>

        <?php
            //check_customer_errors();
        ?>
        <form action = "{{route('customer.store')}}" method="POST">
            @csrf
            <div class="form-row mt-4">
                <div class="form-group col">
                    <label for="salusalutation_idtation">Anrede</label>
                    <select name="salutation_id" id="salutation_id" class="form-control">
                        @foreach($salutations as $salutation)
                            <option value="{{ $salutation->id }}" {{ $salutation->id == 1 ? 'selected' : '' }}>
                                {{ $salutation->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col">
                    <label for="customername">Titel</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php if(isset($_SESSION['customerData']['title'])) { echo $_SESSION['customerData']['title']; } ?>">

                </div>
            </div>
                <!--Kundenname oder Firmenname-->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="customername">Kundenname</label>
                    <input type="hidden" class="form-control" id="client_id" name="client_id" value=1>
                    <input type="hidden" class="form-control" id="emailsubject" name="emailsubject" value="">
                    <input type="hidden" class="form-control" id="emailbody" name="emailbody" value="">
                    <input type="text" class="form-control" id="customername" name="customername" value="<?php if(isset($_SESSION['customerData']['customername'])) { echo $_SESSION['customerData']['customername']; } ?>">
                </div>

                <div class="form-group col-md-6">
                    <label for="companyname">Firmenname</label>
                    <input type="text" class="form-control" id="companyname" name="companyname" value="<?php if(isset($_SESSION['customerData']['companyname'])) { echo $_SESSION['customerData']['companyname']; } ?>">
                </div>
            </div>

                <!--Adresse PLZ und Ort-->
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="address">Adresse</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php if(isset($_SESSION['customerData']['address'])) { echo $_SESSION['customerData']['address']; } ?>">
                </div>

                <div class="form-group col-md-3">
                    <label for="postalcode">Postleitzahl</label>
                    <input type="text" class="form-control" id="postalcode" name="postalcode" value="<?php if(isset($_SESSION['customerData']['postalcode'])) { echo $_SESSION['customerData']['postalcode']; } ?>">
                </div>

                <div class="form-group col-md-4">
                    <label for="location">Ort</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php if(isset($_SESSION['customerData']['location'])) { echo $_SESSION['customerData']['location']; } ?>">
                </div>
            </div>

                <!-- Land und UID -->
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="country">Land</label>
                    <input type="text" class="form-control" id="country" name="country" value="Österreich">
                </div>
                <div class="form-group col-md-4">
                    <label for="tax_id">UID</label>
                    <input type="text" class="form-control" id="tax_id" name="tax_id" value="<?php if(isset($_SESSION['customerData']['tax_id'])) { echo $_SESSION['customerData']['taxid']; } ?>">
                </div>
            </div>

                <!-- Telefonnummer, Fax und Email-->
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="phone">Telefonnummer</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php if(isset($_SESSION['customerData']['phonenumber'])) { echo $_SESSION['customerData']['phone']; } ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="fax">Fax</label>
                    <input type="text" class="form-control" id="fax" name="fax" value="<?php if(isset($_SESSION['customerData']['fax'])) { echo $_SESSION['customerData']['fax']; } ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="email">E-Mail</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php if(isset($_SESSION['customerData']['email'])) { echo $_SESSION['customerData']['email']; } ?>">
                </div>
            </div>

                <!--Dropdown-Menü für Conditions-->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="condition_id">Konditionen</label>
                    <select class="form-control" id="condition_id" name="condition_id">
                        @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}">{{ $condition->conditionname }}</option>
                    @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Neuen Kunden anlegen</button>
            </div>
            <div class="row"></div>
        </form>


    </div>

</x-layout>

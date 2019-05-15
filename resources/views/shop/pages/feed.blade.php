@extends('layout')

@section('content')
    <div id="wrapper">
        @include('shop.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')

                <div class="card">
                    <div class="card-header">
                        {{__('titles.feed_example')}}
                    </div>
                    <div class="card-body">
                        <code>
                            <pre>
{{'<?xml version="1.0" encoding="utf-8"?>
<SHOP>
  <SHOPITEM>
    <EAN>6417182041488</EAN>
    <DELIVERY_DATE>0</DELIVERY_DATE>
    <DELIVERY>
      <DELIVERY_ID>CESKA_POSTA</DELIVERY_ID>
      <DELIVERY_PRICE>120</DELIVERY_PRICE>
      <DELIVERY_PRICE_COD>120</DELIVERY_PRICE_COD>
    </DELIVERY>
    <DELIVERY>
      <DELIVERY_ID>PPL</DELIVERY_ID>
      <DELIVERY_PRICE>90</DELIVERY_PRICE>
      <DELIVERY_PRICE_COD>120</DELIVERY_PRICE_COD>
    </DELIVERY>
    <DELIVERY>
      ...
    </DELIVERY>
    <IMGURL>http://obchod.cz/mobily/nokia-5800/obrazek.jpg</IMGURL>
    <PRICE>6000</PRICE>
    <MANUFACTURER>NOKIA</MANUFACTURER>
    <LANG>
        <LANG_ABBREVIATION>cs<LANG_ABBREVIATION/>
        <PRODUCT>Nokia 5800</PRODUCT>
        <DESCRIPTION>Klasický s plným dotykovým uživatelským rozhraním</DESCRIPTION>
        <URL>http://obchod.cz/mobily/nokia-5800</URL>
        <CATEGORY>Mobilní telefony</CATEGORY>
        <PARAM>
          <PARAM_NAME>Barva</PARAM_NAME>
          <VAL>černá</VAL>
        </PARAM>
    </LANG>
    <LANG>
        <LANG_ABBREVIATION>en<LANG_ABBREVIATION/>
        <PRODUCT>Nokia 5800</PRODUCT>
        <DESCRIPTION>Classic with full touch user interface</DESCRIPTION>
        <URL>http://obchod.cz/mobily/nokia-5800</URL>
        <CATEGORY>Mobile phones</CATEGORY>
        <PARAM>
          <PARAM_NAME>Color</PARAM_NAME>
          <VAL>black</VAL>
        </PARAM>
    </LANG>
    <LANG>
    ...
    </LANG>
  </SHOPITEM>
  <SHOPITEM>
  ...
  </SHOPITEM>
</SHOP>'}}
                            </pre>
                        </code>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        {{__('titles.feed_mandatory')}}
                    </div>
                    <div class="card-body">
                        <ul class="list-inline">
                            <li class="list-inline-item">SHOP</li>
                            <li class="list-inline-item">SHOPITEM</li>
                            <li class="list-inline-item">DELIVERY</li>
                            <li class="list-inline-item">DELIVERY_ID</li>
                            <li class="list-inline-item">DELIVERY_PRICE</li>
                            <li class="list-inline-item">DELIVERY_PRICE_COD</li>
                            <li class="list-inline-item">PRICE</li>
                            <li class="list-inline-item">MANUFACTURER</li>
                            <li class="list-inline-item">LANG</li>
                            <li class="list-inline-item">LANG_ABBREVIATION</li>
                            <li class="list-inline-item">PRODUCT</li>
                            <li class="list-inline-item">URL</li>
                            <li class="list-inline-item">CATEGORY</li>
                            <li class="list-inline-item">PARAM_NAME</li>
                            <li class="list-inline-item">VAL</li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        {{__('titles.feed_optional')}}
                    </div>
                    <div class="card-body">
                        <ul class="list-inline">
                            <li class="list-inline-item">EAN</li>
                            <li class="list-inline-item">DELIVERY_DATE</li>
                            <li class="list-inline-item">IMGURL</li>
                            <li class="list-inline-item">DESCRIPTION</li>
                            <li class="list-inline-item">PARAM</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
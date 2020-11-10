<?php  



/**
 * reports controller
 */

// pdf
use Dompdf\ Dompdf;

class Reports extends MainController
{
    /**
     * manage crud operations for reports
     */

    // iniatialize models
    private $customer;
    private $menu;
    private $singleorder;
    private $order;
    private $user;
    private $receipt;


    private $doc;


    private $img;



    


    public function __construct()
    {
        // check if user is logged in
        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");            
        }

        // load models 
        $this->customer = $this->model('Customer');
        $this->menu = $this->model('Menu');
        $this->singleorder = $this->model('Singleorder');
        $this->order = $this->model('Order');
        $this->user = $this->model('User');
        $this->receipt = $this->model('Receipt');

        $this->doc = new Dompdf();

        $this->img = get_img('logo.png');


    }



    // reports home page
    public function home()
    {
    	// iniatilaize data
    	$data = [];
    	// load view with data
    	$this->view('reports/reports', $data);
    }








    public function daily()
    {
       if($_SERVER['REQUEST_METHOD'] == "POST")  {

            $from = post_data('from');
            $to = post_data('to');

            if($from == "") {
                $from = date('Y-m-d');
            }

            if($to == "") {
                $to = date('Y-m-d');
            }


            $data = $this->singleorder->getDailyReport($from, $to);

            $count = $data['count'];

            $amount = 0;
            foreach ($data['data'] as $a) {
                $amount += $a->singleorder_total;
            }
            $amount = number_format($amount);

            // die(print_r($data['data']));
            $html = "
                    <style>
                        body {
                            font: normal 13px Arial, sans-serif;
                        }
                        table {
                            border: solid 1px #DDEEEE;
                            border-collapse: collapse;
                            border-spacing: 0;
                            font: normal 13px Arial, sans-serif;
                            
                        }
                        table thead th {
                            background-color: #DDEFEF;
                            border: solid 1px #DDEEEE;
                            color: #336B6B;
                            padding: 6px;
                            text-align: left;
                            text-shadow: 1px 1px 1px #fff;
                        }
                        table tbody td {
                            border: solid 1px #DDEEEE;
                            color: #333;
                            padding: 6px;
                            text-shadow: 1px 1px 1px #fff;
                        }
                        .main {
                            width: 100%;
                        }
                        .summary {
                            width: 30%;
                            margin: 100px auto;
                        }
                        .summary tr th, .summary tr td {
                            padding: 10px;
                        }
                        .bg-white {
                            background: #ccc;
                            color: #000;
                        }
                        .page_break { page-break-before: always; }
                            
                        </style>";
            
            $html .= "<center>
                        <img src='$this->img' width='150'>
                        <h2>Sales Summary <br>Period Between $from to $to</h2>
                      </center>
                     <table class='summary'>
                        <tr>
                            <th align='left'>Total Sales</th>
                            <td align='right'>$count</td>
                        </tr>
                        <tr>
                            <th align='left'>Total Amount</th>
                            <td align='right'>Kes $amount</td>
                        </tr>
                      </table>
                      ";


            $html .= "<div class='page_break'></div>";


            $html .= "
            <h3 align='center'>Sales Detailed Data <br> $from to $to ($count sales)</h3>
            
                <table class='main'>
                    <thead>
                    <tr>
                      <th width='25%'>Customer Name</th>
                      <th width='15%'>Date</th>
                      <th width='10%'>Order Id</th>
                      <th width='15%'>Type</th>
                      <th width='10%'>Total</th>
                      <th width='10%'>Paid</th>
                      <th width='10%'>Balance</th>
                      </tr>
                    </thead>
                    <tbody>";

            foreach ($data['data'] as $v) {
                $date = formatedDateShow($v->singleorder_at);
                $customer_name;
                $customer_contacts;
                if($v->customer_name == "Customer") {
                    $customer_name = $v->customer_name ." - ".$v->id;
                } else {
                    $customer_name = $v->customer_name;
                }

                if ($v->customer_contacts == "") {
                    $customer_contacts = "Not Provided";
                } else {
                    $customer_contacts = $v->customer_contacts;
                }

                $name = $customer_name . ", ". $customer_contacts;
                
                $order = $this->order->get_orders_for_sigleorder($v->id);
                $html .= "<tr>
                            <td>$name</td>
                            <td>$date</td>
                            <td>$v->id</td>
                            <td>$v->singleorder_table</td>
                            <td align='right'>$v->singleorder_total</td>
                            <td align='right'>$v->receipt_paid</td>
                            <td align='right'>$v->receipt_balance</td>
                       </tr>
                        <tr>
                <td colspan='7'>
                    <table class='main'>
                        <tr class='bg-white'>
                            <th width='40%' rowspan='5'>
                                Orders For This Customer
                            </th>
                            <th width='10'>
                                Id
                            </th>
                            <th >
                                Order Item
                            </th>
                            <th>
                                Order Quantity
                            </th>
                            <th>
                                Price Per
                            </th>
                            <th align='right'>
                                Order Amount
                            </th>
                        </tr>
                        ";
                        $orders = $this->order->get_orders_for_sigleorder($v->id)['data'];
                        foreach ($orders as $v) {
                        $html.="
                        
                            <tr class='bg-white'>
                                
                                <th>
                                    $v->id
                                </th>
                                <td>
                                    $v->menu_item
                                </td>
                                <td>
                                    $v->order_price_per_item
                                </td>
                                <td>
                                    $v->order_quantity
                                </td>
                                <td align='right'>
                                    $v->order_total
                                </td>
                            </tr>
                        ";
                        }
                        $html .= "
                    </table>
                </td>
            </tr>
                       ";                 
            }


            $html .= "</tbody></table>";

            

            
            
            




            $this->doc->loadHtml($html);           
            $this->doc->setPaper('A4', 'landscape');  
            $this->doc->render();  
            $this->doc->stream("Sales for $from - $to", array("Attachment" =>1) );  


            // die($html);
       

       }  
    }













    public function today()
    {
       if($_SERVER['REQUEST_METHOD'] == "POST")  {

            $data = $this->singleorder->getTodaysReport();

            $count = $data['count'];

            $amount = 0;
            foreach ($data['data'] as $a) {
                $amount += $a->singleorder_total;
            }
            $amount = number_format($amount);

            $today = date('d - M - Y');


            $html = "
                    <style>
                        body {
                            font: normal 13px Arial, sans-serif;
                        }
                        table {
                            border: solid 1px #DDEEEE;
                            border-collapse: collapse;
                            border-spacing: 0;
                            font: normal 13px Arial, sans-serif;
                            
                        }
                        table thead th {
                            background-color: #DDEFEF;
                            border: solid 1px #DDEEEE;
                            color: #336B6B;
                            padding: 6px;
                            text-align: left;
                            text-shadow: 1px 1px 1px #fff;
                        }
                        table tbody td {
                            border: solid 1px #DDEEEE;
                            color: #333;
                            padding: 6px;
                            text-shadow: 1px 1px 1px #fff;
                        }
                        .main {
                            width: 100%;
                        }
                        .summary {
                            width: 30%;
                            margin: 100px auto;
                        }
                        .summary tr th, .summary tr td {
                            padding: 10px;
                        }
                        .bg-white {
                            background: #ccc;
                            color: #000;
                        }
                        .page_break { page-break-before: always; }
                            
                        </style>";

            $html .= "<center>
                        <img src='$this->img' width='150'>
                        <h2>Sales Summary <br>For $today</h2>
                      </center>
                     <table class='summary'>
                        <tr>
                            <th align='left'>Total Sales</th>
                            <td align='right'>$count</td>
                        </tr>
                        <tr>
                            <th align='left'>Total Amount</th>
                            <td align='right'>Kes $amount</td>
                        </tr>
                      </table>
                      ";

            $html .= "<div class='page_break'></div>";

            $html .= "
            <h3 align='center'>Today's Detailed Sales ($count sales)</h3>
                <table class='main'>
                    <thead>
                    <tr>
                      <th width='30%'>Customer Name</th>
                      <th width='15%'>Date</th>
                      <th width='10%'>Order Id</th>
                      <th width='10%'>Type</th>
                      <th width='10%'>Total</th>
                      <th width='10%'>Paid</th>
                      <th width='10%'>Balance</th>
                      </tr>
                    </thead>
                    <tbody>";

                    foreach ($data['data'] as $v) {
                        $date = formatedDateShow($v->singleorder_at);
                        $customer_name;
                        $customer_contacts;
                        if($v->customer_name == "Customer") {
                            $customer_name = $v->customer_name ." - ".$v->id;
                        } else {
                            $customer_name = $v->customer_name;
                        }

                        if ($v->customer_contacts == "") {
                            $customer_contacts = "Not Provided";
                        } else {
                            $customer_contacts = $v->customer_contacts;
                        }

                        $name = $customer_name . ", ". $customer_contacts;

                        $name = $customer_name . ", ". $customer_contacts;
                        
                        $order = $this->order->get_orders_for_sigleorder($v->id);
                        $html .= "<tr>
                                    <td>$name</td>
                                    <td>$date</td>
                                    <td>$v->id</td>
                                    <td>$v->singleorder_table</td>
                                    <td align='right'>$v->singleorder_total</td>
                                    <td align='right'>$v->receipt_paid</td>
                                    <td align='right'>$v->receipt_balance</td>
                               </tr>
                                <tr>
                        <td colspan='7'>
                            <table class='main'>
                                <tr class='bg-white'>
                                    <th width='40%' rowspan='5'>
                                        Orders For This Customer
                                    </th>
                                    <th width='10'>
                                        Id
                                    </th>
                                    <th >
                                        Order Item
                                    </th>
                                    <th>
                                        Order Quantity
                                    </th>
                                    <th>
                                        Price Per
                                    </th>
                                    <th align='right'>
                                        Order Amount
                                    </th>
                                </tr>
                                ";
                                $orders = $this->order->get_orders_for_sigleorder($v->id)['data'];
                                foreach ($orders as $v) {
                                $html.="
                                
                                    <tr class='bg-white'>
                                        
                                        <th>
                                            $v->id
                                        </th>
                                        <td>
                                            $v->menu_item
                                        </td>
                                        <td>
                                            $v->order_price_per_item
                                        </td>
                                        <td>
                                            $v->order_quantity
                                        </td>
                                        <td align='right'>
                                            $v->order_total
                                        </td>
                                    </tr>
                                ";
                                }
                        $html .= "
                    </table>
                </td>
            </tr>
                       ";                 
            }

            $html .= "</tbody>
                    </table>";


            $this->doc->loadHtml($html);           
            $this->doc->setPaper('A4', 'landscape');  
            $this->doc->render();  
            $this->doc->stream("Todays Sales ", array("Attachment" =>1) );  


            // die($html);
       

       }  
    }
























    public function monthly()
    {
       if($_SERVER['REQUEST_METHOD'] == "POST")  {

            $month = post_data('month');
            $year = post_data('year');

            $data = $this->singleorder->getMonthlyReport($month, $year);

            $count = $data['count'];

            $amount = 0;
            foreach ($data['data'] as $a) {
                $amount += $a->ttl;
            }
            $amount = number_format($amount);

            $today = date('d - M - Y');


            $html = "
                    <style>
                        body {
                            font: normal 13px Arial, sans-serif;
                        }
                        table {
                            border: solid 1px #DDEEEE;
                            border-collapse: collapse;
                            border-spacing: 0;
                            font: normal 13px Arial, sans-serif;
                            
                        }
                        table thead th {
                            background-color: #DDEFEF;
                            border: solid 1px #DDEEEE;
                            color: #336B6B;
                            padding: 6px;
                            text-align: left;
                            text-shadow: 1px 1px 1px #fff;
                        }
                        table tbody td {
                            border: solid 1px #DDEEEE;
                            color: #333;
                            padding: 6px;
                            text-shadow: 1px 1px 1px #fff;
                        }
                        .main {
                            width: 100%;
                        }
                        .summary {
                            width: 30%;
                            margin: 100px auto;
                        }
                        .summary tr th, .summary tr td {
                            padding: 10px;
                        }
                        .bg-white {
                            background: #ccc;
                            color: #000;
                        }
                        .page_break { page-break-before: always; }
                            
                        </style>";

            $html .= "<center>
                        <img src='$this->img' width='150'>
                        <h2>Monthly Sales Summary <br>For $month, $year</h2>
                      </center>
                     <table class='summary'>
                        <tr>
                            <th align='left'>Total Sales</th>
                            <td align='right'>$count</td>
                        </tr>
                        <tr>
                            <th align='left'>Total Amount</th>
                            <td align='right'>Kes $amount</td>
                        </tr>
                      </table>
                      ";

            $html .= "<div class='page_break'></div>";

            $html .= "
            <h3 align='center'>Today's Detailed Sales ($count sales)</h3>
                <table class='main'>
                    <thead>
                    <tr>
                      <th width='50%'>Date</th>
                      <th width='15%'>Sales</th>
                      <th width='10%'>Amount</th>
            
                      </tr>
                    </thead>
                    <tbody>";

                    foreach ($data['data'] as $v) {
                       
                        
                        $html .= "<tr>
                                    <td>$v->at</td>
                                   
                                    <td>$v->sales</td>
                                   
                                    <td align='right'>$v->ttl</td>
                                    
                                  </tr>";                 
            }

            $html .= "</tbody>
                    </table>";


            $this->doc->loadHtml($html);           
            $this->doc->setPaper('A4', 'landscape');  
            $this->doc->render();  
            $this->doc->stream("Monthly sales, $month - $year ", array("Attachment" =>1) );  


            die($html);
       

       }  
    }





































    public function receipt($id)
    {
        // iniatilize data
        $data = [];
        // fetch customer with id
        $singleorder = $this->singleorder->find($id);
        // check if null is returned
        if($singleorder['count']>0) {
            // not null, load view with customer
            $so = $singleorder['data'];

            // load orders for this single order
            $orders = $this->order->get_orders_for_sigleorder($so->id);
            // get receipt
            $receipt = $this->receipt->get_receipt_for_sigleorder($so->id)['data'];
            // get customer
            $customer = $this->customer->find($so->singleorder_customer_id)['data'];


            //

            $name = $customer->customer_name;
            if ($name == "Customer") {
                $name = $name . " " . $customer->id;
            }

            $balance = number_format($receipt->receipt_balance);
            $total = number_format($receipt->receipt_total_amount);
            $paid = number_format($receipt->receipt_paid);
            $od_no = $so->id;




        
            

            $html = '';
            $html .= "
                <style>
                    table {
                        border: solid 1px #DDEEEE;
                        border-collapse: collapse;
                        border-spacing: 0;
                        font: normal 13px Arial, sans-serif;
                    }table  th {
                        background-color: #DDEFEF;
                        border: solid 1px #DDEEEE;
                        color: #336B6B;
                        padding: 10px;
                        text-align: left;
                        text-shadow: 1px 1px 1px #fff;
                    }table  td {
                        border: solid 1px #DDEEEE;
                        color: #333;
                        padding: 10px;
                        text-shadow: 1px 1px 1px #fff;
                    }
                    .bg-white {
                        background: #ccc;
                    }
                </style>

                <table>
    
                    <tr>
                        <th colspan='3'>
                            <h2>
                                Millenium Cafe
                            </h2>
                        </th>
                    </tr>
                    <tr>
                        <th width='40%'>
                            Customer Name
                        </th>
                        <td colspan='2'>
                            $name
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Customer Order Number
                        </th>
                        <td colspan='2'>
                            $od_no
                        </td>
                    </tr>
                    <tr>
                        <th colspan='3'>
                            Order Items
                        </th>
                    </tr>
            ";

            foreach ($orders['data'] as $v) {
                    $order_item = $v->menu_item;
                    $order_quantity = $v->order_quantity;
                    $order_total = $v->order_total;


                    $html .= "
                        <tr>
                            <td>
                                $order_item
                            </td>
                            <td>
                                $order_quantity
                            </td>
                            <td>
                                $order_total
                            </td>
                        </tr>
                    ";
            }

            $htm .= "
                <tr>
                    <th>
                        Totals
                    </th>
                    <td></td>
                    <th>
                        $total
                    </th>
                </tr>
                <tr>
                    <th>
                        Paid
                    </th>
                    <td></td>
                    <th>
                        $paid
                    </th>
                </tr>
                <tr>
                    <th>
                        Balance
                    </th>
                    <td></td>
                    <th>
                        $balance
                    </th>
                </tr>

                </table>
            ";


            die($html);


        }
    }








}
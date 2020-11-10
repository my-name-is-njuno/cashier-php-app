

<style>
	table {
	    border: solid 1px #DDEEEE;
	    border-collapse: collapse;
	    border-spacing: 0;
	    font: normal 13px Arial, sans-serif;
	}table thead th {
	    background-color: #DDEFEF;
	    border: solid 1px #DDEEEE;
	    color: #336B6B;
	    padding: 10px;
	    text-align: left;
	    text-shadow: 1px 1px 1px #fff;
	}table tbody td {
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
	
	<thead>
	  <th width='25%'>Customer Name</th>
      <th width='15%'>Date</th>
      <th width='10%'>Order Id</th>
      <th width='15%'>Type</th>
      <th width='10%'>Total</th>
      <th width='10%'>Paid</th>
      <th width='10%'>Balance</th>
	</thead>

	<tbody>
		<?php foreach ($data['data'] as $v): ?>
			<tr>
                <td>$v->customer_name</td>
                <td>$date</td>
                <td>$v->id</td>
                <td>$v->singleorder_table</td>
                <td align='right'>$v->singleorder_total</td>
                <td align='right'>$v->receipt_paid</td>
                <td align='right'>$v->receipt_balance</td>
            </tr>
            <tr>
            	<td colspan="8">
            		<table>
            			<tr class="bg-white">
            				<th width="20%">
            					Order Id
            				</th>
            				<th width="10">
            					Order Id
            				</th>
            				<td>
            					Order Item
            				</td>
            				<td>
            					Order Quantity
            				</td>
            				<td>
            					Order Amount
            				</td>
            			</tr>
            			<?php foreach ($orders as $v): ?>
            				<tr class="bg-white">
            					<th></th>
	            				<th>
	            					<?php $v->id; ?>
	            				</th>
	            				<td>
	            					<?php $v->menu_item ?>
	            				</td>
	            				<td>
	            					<?php $v->order_quantity ?>
	            				</td>
	            				<td>
	            					<?php $v->order_total ?>
	            				</td>
	            			</tr>
            			<?php endforeach ?>
            		</table>
            	</td>
            </tr>
		<?php endforeach ?>
	</tbody>
</table>
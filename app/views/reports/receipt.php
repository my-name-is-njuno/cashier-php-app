

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
		<th colspan='2'>
			<h2>
				Millenium Cafe
			</h2>
		</th>
	</tr>
	<tr>
		<th width='60%'>
			Customer Name
		</th>
		<td>
			
		</td>
	</tr>
	<tr>
		<th>
			Customer Order Number
		</th>
		<td>
			
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			Order Items
		</td>
	</tr>

	<?php foreach ($collection as $value): ?>
		<tr>
			<td>
				
			</td>
			<td>
				
			</td>
			<td>
				
			</td>
		</tr>
	<?php endforeach ?>
	<tr>
		<th>
			Totals
		</th>
		<td></td>
		<td>
			
		</td>
	</tr>


	<tr>
		<th>
			Paid
		</th>
		<td></td>
		<td>
			
		</td>
	</tr>


	<tr>
		<th>
			Balance
		</th>
		<td>
		</td>
		<td>
			
		</td>
	</tr>

</table>


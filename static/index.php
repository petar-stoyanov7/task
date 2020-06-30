<?php
$title = "Автомобилни разходи";
// include("header.php");
// include("top-toolbar.php");
?>
<div class="container">
<h3><font color="red" weight="bold">Това е примерна страница!</font></h3>
<br>Добре дошли в страницата за автомобилни разходи. Това, което виждате е пример за съдържанието на сайта. С помощта на този сайт може да управлявате разходите по автомобила си и да извличате детайлна статистика. Ако желаете да използвате услугите - нужно е да имате регистрация. Регистрация може да направите, като натиснете <a href="../register.php">тук</a>.<br>
За вече регистрирани потребители - <a href="../login.php">тук</a>.
</div>
<!-- Content -->
	<div class="container">
		<h3>Добре дошъл, Иван Иванов!</h3>
		Брой автомобили: 2<br>
		Общо похарчени: 8200 лв.<br>

	</div>
	<div class="container">
	<h3>Автомобили:</h3>
		<div class="element">
		<h4>Автомобил 1:</h4>
		BMW M3 2010<br>
		<b>Километри</b>: 80000km<br>
		<b>Похарчени:</b> 6800 лв.
		</div>

		<div class="element">
		<h4>Автомобил 2:</h4>
		Wolkswagen Golf 2001<br>
		<b>Километри:</b> 150000km<br>
		<b>Похарчени:</b> 1400 лв.
		</div>
	</div>

	<div class="container">
		<h3>Последни пет:</h3>
		<table class="expenses">
			<tr>
				<th>Километри</th>
				<th>Автомобил</th>
				<th>Тип разход</th>
				<th>Стойност</th>
			</tr>
			<tr>
				<td>78950</td>
				<td>BMW M3</td>
				<td>Гориво</td>
				<td>99</td>				
			</tr>
			<tr>
				<td>148950</td>
				<td>Wolkswagen Golf</td>
				<td>Винетка</td>
				<td>97</td>
			</tr>
			<tr>
				<td>78850</td>
				<td>BMW M3</td>
				<td>Ремонт</td>
				<td>1200</td>
			</tr>
			<tr>
				<td>147800</td>
				<td>Wolkswagen Golf</td>
				<td>Застраховка</td>
				<td>100</td>
			</tr>
			<tr>
				<td>147770</td>
				<td>Wolkswagen Golf</td>
				<td>Гориво</td>
				<td>20</td>
			</tr>
		</table>
	</div>
<?php
// include("footer.php");
?>
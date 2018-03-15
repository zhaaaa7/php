## jQuery dynamically add element.

Assignment specification: https://www.wa4e.com/assn/res-position/

Demo: http://sjzhao.byethost33.com/week11/

email: umsi@umich.edu
password: php123

## NOtes
1. jQuery is an object
2. Include the library
```
<script type="text/javascript" src=“jquery.min.js"></script>
```
3. $ is a global variable: call the function and returns an object
```
 $(document): document is a parameter
```
4. 
```javascript
$(window).resize(function()
 {
console.log('.resize() called. width='+$(window).width()+' height='+$(window).height());
})
```
I have a function, please call it every time the window is resized

5.toggle: change the css “diaplay”
6. watch and do something with input fields

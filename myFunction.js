<script type = "text/javascript">
      function myFunction() {    
         alert("starting myFunction");
        var input, filter, table, tr, td, i, txtValue,trid;
        var $n="0";
        input = document.getElementById("myInput");
        $lastsearch = document.getElementById("myInput").value;
        document.cookie = "search=" + $lastsearch;
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
     
        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) 
         {
            td = tr[i].getElementsByTagName("td")[$n];
            if (td) {
                        var ele = tr[i].getElementsByTagName('input');  
                        for (x = 0; x < ele.length; x++)
                            {
                                 if (ele[x].type == 'text')
                                     {
                                          if(ele[x].value == input.value)
                                                {
                                                   //td.style.height = "20px";          
                                                   //tr[i].style.display = "inline-block";
                                                   console.log('Value: ' + ele[x].value);

                                                   console.log('tr id: ' + tr[i].id);  
                                                   trid=tr[i].id;                                     //txtValue = td.textContent || td.innerText;
                                                   //found = true;
                                                   
                                                   
                                                } else {
                                                   //tr[i].style.display = "none";
                                                   
                                                   //found = false;
                                                   //tr[i].height= "300px";
                                                   //td.style.height = "20px";
                                                }
                                    }
                           }
                           
                     }
         }
         for (i = 0; i < tr.length; i++) 
         {
            
            if(tr[i].id === trid){tr[i].style.display="table";}else{tr[i].style.display="none";}
            if(input.value === ""){tr[i].style.display="table";}
         }
        
		}

        </script>
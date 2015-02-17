## Ruby To Dot ##

Install
-------

`$ composer install`

Generate ruby classes file 
-------

    $ cd features_folder
    $ find . -type f -name '*_page.rb' -exec awk '1' {} + > rubytodot_folder/classes.txt
    $ find . -type f -name '*_section.rb' -exec awk '1' {} + >> rubytodot_folder/classes.txt

Generate Dot file ( > php 5.4)
---------------
`$ php index.php > dot_results.txt`

[Make the graph](http://www.webgraphviz.com/)
-------------

> Written with [StackEdit](https://stackedit.io/).
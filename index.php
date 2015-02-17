<?php
# Make classes.txt
# cd /home/lighta/projects/Horyou/desktop/cucumber/features
# find . -type f -name '*_page.rb' -exec awk '1' {} + >> ~/projects/hryCucumberClassDiagram/classes.txt
# find . -type f -name '*_section.rb' -exec awk '1' {} + >> ~/projects/hryCucumberClassDiagram/classes.txt
error_reporting(E_ALL);
ini_set("error_display", "on");

require 'vendor/autoload.php';

// $graph = new Alom\Graphviz\Digraph('G');
// $graph
//     ->subgraph('cluster_1')
//         ->attr('node', array('style' => 'filled', 'fillcolor' => 'blue'))
//         ->node('A')
//         ->node('B')
//         ->edge(array('b0', 'b1', 'b2', 'b3'))
//     ->end()
//     ->edge(array('A', 'B', 'C'))
// ;
// echo $graph->render();
// exit;
$input = file_get_contents("classes.txt");

preg_match_all("#class (.*) <#i", $input, $matches);
$classes = $matches[1];
$classRelations = array_fill_keys($classes, []);

$graph = new Alom\Graphviz\Digraph('G');
// echo "<pre>";

$lines = explode("\n", $input);
$currentClass = "";
foreach($lines as $l)
{
    if(strpos($l, "class")===0)
    {
        if(preg_match("#class (.*) <#i", $l, $match))
        {
            $currentClass = $match[1];
            $classRelations[$currentClass] = [];
        }
    }
    else if($l != "end" && $currentClass)
    {
        if(preg_match('#('.implode('|', $classes).'),#i', $l, $matches))
        {
            $classRelations[$currentClass][] = substr($matches[0], 0, -1);
        }
    }
}

// $graph->attr('graph', ['splines'=>'ortho', 'nodesep' =>1]);
// $graph->attr('node', ['shape'=>'record']);
$graph->attr('node', ['style'=>'filled']);
// $graph->set('ratio', 'fill');

$classPages = $classSections = [];
foreach($classes as $c)
{
    $found = false;
    foreach($classRelations as $key => $rels)
    {
        if(in_array($c, $rels))
        {
            $found = true;
            break;
        }
        else if($key == $c && $rels)
        {
            $found = true;
        }
    }
    if(!$found) continue;

    if(strpos($c, "Page"))
    {
        $graph->node($c, ['color'=>'0.355 0.563 1.000']);
        // $classPages[] = $c;
    }
    else
    {
        // $classSections[] = $c;
        $graph->node($c, ['color'=>'0.650 0.200 1.000']);
    }
}
foreach($classRelations as $className => $relations)
{

    // if(strpos($className, "Page"))
    //     $graph->subgraph('cluster_'.$className)
    //         ->edge(array_merge($relations, [$className]))
    //         ->set('label', $className)
    //         ->end();
    foreach($relations as $rel)
    {
        $graph->edge([$rel, $className]);
    }
}
echo $graph->render();




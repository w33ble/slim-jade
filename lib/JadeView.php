<?php

/**
 * JadeView
 *
 * The JadeView is a custom View class that renders templates using the Jade
 * template language (http://jade-lang.com).
 * 
 * JadeView makes use if jade.php from Konstantin Kudryashov aka everzet
 * (https://github.com/everzet/jade.php)
 *
 * Two fields that you, the developer, will need to change are:
 * - jadeDirectory
 * - jadeTemplatesDirectory
 *
 * @package Slim
 * @author  Joe Fleming <http://joefleming.net>
 */
class JadeView extends Slim_View {
	/**
     * @var string The path to the Twig code directory WITHOUT the trailing slash
     */
	public static $jadeDirectory = null;

	/**
     * @var string The path to the templates folder WITHOUT the trailing slash
     */
	public static $jadeTemplateDirectory = 'views/';

	/**
     * @var persistent instance of the Jade object
     */
	private static $jadeInstance = null;

	/**
    * Render Jade Template
    *
    * This method will output the rendered template content
    *
    * @param    string $template The path to the Jade template, relative to the  templates directory.
    * @return   void
    */
	public function render( $template ) {
		$instance = self::getInstance();
		if (substr($template, 0, 1) != '/') {
			$template = self::$jadeTemplateDirectory . $template; }
		if (!stristr($template, '.jade')) {
			$template = $template . '.jade'; }

		//assign local variables
		foreach($this->data as $key=>$value) {
			$$key = $value;
		}

		return eval('?>' . $instance->render($template));
	}

	/**
     * Creates new Jade object instance if it doesn't already exist, and returns it.
     *
     * @throws RuntimeException If Jade lib directory does not exist
     * @return Jade Instance
     */
	public function getInstance() {
		if( ! (self::$jadeInstance instanceof Jade) ) {
			if(! is_dir(self::$jadeDirectory) ) {
				throw new RuntimeException('Jade.php directory does not exist : '.self::$jadeDirectory);
			}

			require_once self::$jadeDirectory . '/vendor/symfony/src/Symfony/Framework/UniversalClassLoader.php';
			$loader = new Symfony\Framework\UniversalClassLoader();
			$loader->registerNamespaces(array('Everzet' => self::$jadeDirectory . 'src'));
			$loader->register();

			$dumper = new Everzet\Jade\Dumper\PHPDumper();
			$dumper->registerVisitor('tag', new Everzet\Jade\Visitor\AutotagsVisitor());
			$dumper->registerFilter('javascript', new Everzet\Jade\Filter\JavaScriptFilter());
			$dumper->registerFilter('cdata', new Everzet\Jade\Filter\CDATAFilter());
			$dumper->registerFilter('php', new Everzet\Jade\Filter\PHPFilter());
			$dumper->registerFilter('style', new Everzet\Jade\Filter\CSSFilter());

			// Initialize parser & Jade
			$parser = new Everzet\Jade\Parser(new Everzet\Jade\Lexer\Lexer());
			self::$jadeInstance = new Everzet\Jade\Jade($parser, $dumper);
		}
		return self::$jadeInstance;
	}
}

?>

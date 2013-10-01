<?php
/**
 * Auto-generated class. CPP syntax highlighting
 * 
 * 
 * Thanks to Aaron Kalin for initial
 * implementation of this highlighter
 *      
 *
 * PHP version 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @copyright  2004-2006 Andrey Demenev
 * @license    http://www.php.net/license/3_0.txt  PHP License
 * @link       http://pear.php.net/package/Text_Highlighter
 * @category   Text
 * @package    Text_Highlighter
 * @version    generated from: cpp.xml
 * @author Aaron Kalin
 * @author Andrey Demenev <demenev@gmail.com>
 *
 */

/**
 * @ignore
 */

require_once 'highlighter/Highlighter.php';

/**
 * Auto-generated class. CPP syntax highlighting
 *
 * @author Aaron Kalin
 * @author Andrey Demenev <demenev@gmail.com>
 * @category   Text
 * @package    Text_Highlighter
 * @copyright  2004-2006 Andrey Demenev
 * @license    http://www.php.net/license/3_0.txt  PHP License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Highlighter
 */
class  Text_Highlighter_CPP extends Text_Highlighter
{
    var $_language = 'cpp';

    /**
     * PHP4 Compatible Constructor
     *
     * @param array  $options
     * @access public
     */
    function Text_Highlighter_CPP($options=array())
    {
        $this->__construct($options);
    }


    /**
     *  Constructor
     *
     * @param array  $options
     * @access public
     */
    function __construct($options=array())
    {

        $this->_options = $options;
        $this->_regs = array (
            -1 => '/((?i)")|((?i)\\\')|((?i)\\{)|((?i)\\()|((?i)\\[)|((?i)[a-z_]\\w*)|((?i)\\b0[xX][\\da-f]+)|((?i)\\b\\d\\d*|\\b0\\b)|((?i)\\b0[0-7]+)|((?i)\\b(\\d*\\.\\d+)|(\\d+\\.\\d*))|((?mi)^[ \\t]*#include)|((?mii)^[ \\t]*#[ \\t]*[a-z]+)|((?i)\\d*\\.?\\d+)|((?i)\\/\\*)|((?i)\\/\\/.+)/',
            0 => '/((?i)(\\\\)")|((?i)\\\\)/',
            1 => '/((?i)(\\\\)\')|((?i)\\\\)/',
            2 => '/((?i)")|((?i)\\\')|((?i)\\{)|((?i)\\()|((?i)\\[)|((?i)[a-z_]\\w*)|((?i)\\b0[xX][\\da-f]+)|((?i)\\b\\d\\d*|\\b0\\b)|((?i)\\b0[0-7]+)|((?i)\\b(\\d*\\.\\d+)|(\\d+\\.\\d*))|((?mi)^[ \\t]*#include)|((?mii)^[ \\t]*#[ \\t]*[a-z]+)|((?i)\\d*\\.?\\d+)|((?i)\\/\\*)|((?i)\\/\\/.+)/',
            3 => '/((?i)")|((?i)\\\')|((?i)\\{)|((?i)\\()|((?i)\\[)|((?i)[a-z_]\\w*)|((?i)\\b0[xX][\\da-f]+)|((?i)\\b\\d\\d*|\\b0\\b)|((?i)\\b0[0-7]+)|((?i)\\b(\\d*\\.\\d+)|(\\d+\\.\\d*))|((?mi)^[ \\t]*#include)|((?mii)^[ \\t]*#[ \\t]*[a-z]+)|((?i)\\d*\\.?\\d+)|((?i)\\/\\*)|((?i)\\/\\/.+)/',
            4 => '/((?i)")|((?i)\\\')|((?i)\\{)|((?i)\\()|((?i)\\[)|((?i)[a-z_]\\w*)|((?i)\\b0[xX][\\da-f]+)|((?i)\\b\\d\\d*|\\b0\\b)|((?i)\\b0[0-7]+)|((?i)\\b(\\d*\\.\\d+)|(\\d+\\.\\d*))|((?mi)^[ \\t]*#include)|((?mii)^[ \\t]*#[ \\t]*[a-z]+)|((?i)\\d*\\.?\\d+)|((?i)\\/\\*)|((?i)\\/\\/.+)/',
            5 => '//',
            6 => '/((?i)")|((?i)<)/',
            7 => '/((?i)")|((?i)\\{)|((?i)\\()|((?i)[a-z_]\\w*)|((?i)\\b0[xX][\\da-f]+)|((?i)\\b\\d\\d*|\\b0\\b)|((?i)\\b0[0-7]+)|((?i)\\b(\\d*\\.\\d+)|(\\d+\\.\\d*))|((?i)\\/\\*)|((?i)\\/\\/.+)/',
            8 => '/((?i)\\$\\w+\\s*:.+\\$)/',
            9 => '/((?i)\\$\\w+\\s*:.+\\$)/',
        );
        $this->_counts = array (
            -1 => 
            array (
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 2,
                10 => 0,
                11 => 0,
                12 => 0,
                13 => 0,
                14 => 0,
            ),
            0 => 
            array (
                0 => 1,
                1 => 0,
            ),
            1 => 
            array (
                0 => 1,
                1 => 0,
            ),
            2 => 
            array (
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 2,
                10 => 0,
                11 => 0,
                12 => 0,
                13 => 0,
                14 => 0,
            ),
            3 => 
            array (
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 2,
                10 => 0,
                11 => 0,
                12 => 0,
                13 => 0,
                14 => 0,
            ),
            4 => 
            array (
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 2,
                10 => 0,
                11 => 0,
                12 => 0,
                13 => 0,
                14 => 0,
            ),
            5 => 
            array (
            ),
            6 => 
            array (
                0 => 0,
                1 => 0,
            ),
            7 => 
            array (
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 2,
                8 => 0,
                9 => 0,
            ),
            8 => 
            array (
                0 => 0,
            ),
            9 => 
            array (
                0 => 0,
            ),
        );
        $this->_delim = array (
            -1 => 
            array (
                0 => 'quotes',
                1 => 'quotes',
                2 => 'brackets',
                3 => 'brackets',
                4 => 'brackets',
                5 => '',
                6 => '',
                7 => '',
                8 => '',
                9 => '',
                10 => 'prepro',
                11 => 'prepro',
                12 => '',
                13 => 'mlcomment',
                14 => 'comment',
            ),
            0 => 
            array (
                0 => '',
                1 => '',
            ),
            1 => 
            array (
                0 => '',
                1 => '',
            ),
            2 => 
            array (
                0 => 'quotes',
                1 => 'quotes',
                2 => 'brackets',
                3 => 'brackets',
                4 => 'brackets',
                5 => '',
                6 => '',
                7 => '',
                8 => '',
                9 => '',
                10 => 'prepro',
                11 => 'prepro',
                12 => '',
                13 => 'mlcomment',
                14 => 'comment',
            ),
            3 => 
            array (
                0 => 'quotes',
                1 => 'quotes',
                2 => 'brackets',
                3 => 'brackets',
                4 => 'brackets',
                5 => '',
                6 => '',
                7 => '',
                8 => '',
                9 => '',
                10 => 'prepro',
                11 => 'prepro',
                12 => '',
                13 => 'mlcomment',
                14 => 'comment',
            ),
            4 => 
            array (
                0 => 'quotes',
                1 => 'quotes',
                2 => 'brackets',
                3 => 'brackets',
                4 => 'brackets',
                5 => '',
                6 => '',
                7 => '',
                8 => '',
                9 => '',
                10 => 'prepro',
                11 => 'prepro',
                12 => '',
                13 => 'mlcomment',
                14 => 'comment',
            ),
            5 => 
            array (
            ),
            6 => 
            array (
                0 => 'quotes',
                1 => 'quotes',
            ),
            7 => 
            array (
                0 => 'quotes',
                1 => 'brackets',
                2 => 'brackets',
                3 => '',
                4 => '',
                5 => '',
                6 => '',
                7 => '',
                8 => 'mlcomment',
                9 => 'comment',
            ),
            8 => 
            array (
                0 => '',
            ),
            9 => 
            array (
                0 => '',
            ),
        );
        $this->_inner = array (
            -1 => 
            array (
                0 => 'string',
                1 => 'string',
                2 => 'code',
                3 => 'code',
                4 => 'code',
                5 => 'identifier',
                6 => 'number',
                7 => 'number',
                8 => 'number',
                9 => 'number',
                10 => 'prepro',
                11 => 'code',
                12 => 'number',
                13 => 'mlcomment',
                14 => 'comment',
            ),
            0 => 
            array (
                0 => 'string',
                1 => 'special',
            ),
            1 => 
            array (
                0 => 'string',
                1 => 'special',
            ),
            2 => 
            array (
                0 => 'string',
                1 => 'string',
                2 => 'code',
                3 => 'code',
                4 => 'code',
                5 => 'identifier',
                6 => 'number',
                7 => 'number',
                8 => 'number',
                9 => 'number',
                10 => 'prepro',
                11 => 'code',
                12 => 'number',
                13 => 'mlcomment',
                14 => 'comment',
            ),
            3 => 
            array (
                0 => 'string',
                1 => 'string',
                2 => 'code',
                3 => 'code',
                4 => 'code',
                5 => 'identifier',
                6 => 'number',
                7 => 'number',
                8 => 'number',
                9 => 'number',
                10 => 'prepro',
                11 => 'code',
                12 => 'number',
                13 => 'mlcomment',
                14 => 'comment',
            ),
            4 => 
            array (
                0 => 'string',
                1 => 'string',
                2 => 'code',
                3 => 'code',
                4 => 'code',
                5 => 'identifier',
                6 => 'number',
                7 => 'number',
                8 => 'number',
                9 => 'number',
                10 => 'prepro',
                11 => 'code',
                12 => 'number',
                13 => 'mlcomment',
                14 => 'comment',
            ),
            5 => 
            array (
            ),
            6 => 
            array (
                0 => 'string',
                1 => 'string',
            ),
            7 => 
            array (
                0 => 'string',
                1 => 'code',
                2 => 'code',
                3 => 'identifier',
                4 => 'number',
                5 => 'number',
                6 => 'number',
                7 => 'number',
                8 => 'mlcomment',
                9 => 'comment',
            ),
            8 => 
            array (
                0 => 'inlinedoc',
            ),
            9 => 
            array (
                0 => 'inlinedoc',
            ),
        );
        $this->_end = array (
            0 => '/(?i)"/',
            1 => '/(?i)\\\'/',
            2 => '/(?i)\\}/',
            3 => '/(?i)\\)/',
            4 => '/(?i)\\]/',
            5 => '/(?i)>/',
            6 => '/(?mi)(?<!\\\\)$/',
            7 => '/(?mi)(?<!\\\\)$/',
            8 => '/(?i)\\*\\//',
            9 => '/(?mi)$/',
        );
        $this->_states = array (
            -1 => 
            array (
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => -1,
                6 => -1,
                7 => -1,
                8 => -1,
                9 => -1,
                10 => 6,
                11 => 7,
                12 => -1,
                13 => 8,
                14 => 9,
            ),
            0 => 
            array (
                0 => -1,
                1 => -1,
            ),
            1 => 
            array (
                0 => -1,
                1 => -1,
            ),
            2 => 
            array (
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => -1,
                6 => -1,
                7 => -1,
                8 => -1,
                9 => -1,
                10 => 6,
                11 => 7,
                12 => -1,
                13 => 8,
                14 => 9,
            ),
            3 => 
            array (
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => -1,
                6 => -1,
                7 => -1,
                8 => -1,
                9 => -1,
                10 => 6,
                11 => 7,
                12 => -1,
                13 => 8,
                14 => 9,
            ),
            4 => 
            array (
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => -1,
                6 => -1,
                7 => -1,
                8 => -1,
                9 => -1,
                10 => 6,
                11 => 7,
                12 => -1,
                13 => 8,
                14 => 9,
            ),
            5 => 
            array (
            ),
            6 => 
            array (
                0 => 0,
                1 => 5,
            ),
            7 => 
            array (
                0 => 0,
                1 => 2,
                2 => 3,
                3 => -1,
                4 => -1,
                5 => -1,
                6 => -1,
                7 => -1,
                8 => 8,
                9 => 9,
            ),
            8 => 
            array (
                0 => -1,
            ),
            9 => 
            array (
                0 => -1,
            ),
        );
        $this->_keywords = array (
            -1 => 
            array (
                0 => -1,
                1 => -1,
                2 => -1,
                3 => -1,
                4 => -1,
                5 => 
                array (
                    'reserved' => '/^(and|and_eq|asm|bitand|bitor|break|case|catch|compl|const_cast|continue|default|delete|do|dynamic_cast|else|for|fortran|friend|goto|if|new|not|not_eq|operator|or|or_eq|private|protected|public|reinterpret_cast|return|sizeof|static_cast|switch|this|throw|try|typeid|using|while|xor|xor_eq|false|true)$/',
                    'types' => '/^(auto|bool|char|class|const|double|enum|explicit|export|extern|float|inline|int|long|mutable|namespace|register|short|signed|static|struct|template|typedef|typename|union|unsigned|virtual|void|volatile|wchar_t)$/',
                    'Common Macros' => '/^(NULL|TRUE|FALSE|MAX|MIN|__LINE__|__DATA__|__FILE__|__TIME__|__STDC__)$/',
                ),
                6 => 
                array (
                ),
                7 => 
                array (
                ),
                8 => 
                array (
                ),
                9 => 
                array (
                ),
                10 => -1,
                11 => -1,
                12 => 
                array (
                ),
                13 => -1,
                14 => -1,
            ),
            0 => 
            array (
                0 => 
                array (
                ),
                1 => 
                array (
                ),
            ),
            1 => 
            array (
                0 => 
                array (
                ),
                1 => 
                array (
                ),
            ),
            2 => 
            array (
                0 => -1,
                1 => -1,
                2 => -1,
                3 => -1,
                4 => -1,
                5 => 
                array (
                    'reserved' => '/^(and|and_eq|asm|bitand|bitor|break|case|catch|compl|const_cast|continue|default|delete|do|dynamic_cast|else|for|fortran|friend|goto|if|new|not|not_eq|operator|or|or_eq|private|protected|public|reinterpret_cast|return|sizeof|static_cast|switch|this|throw|try|typeid|using|while|xor|xor_eq|false|true)$/',
                    'types' => '/^(auto|bool|char|class|const|double|enum|explicit|export|extern|float|inline|int|long|mutable|namespace|register|short|signed|static|struct|template|typedef|typename|union|unsigned|virtual|void|volatile|wchar_t)$/',
                    'Common Macros' => '/^(NULL|TRUE|FALSE|MAX|MIN|__LINE__|__DATA__|__FILE__|__TIME__|__STDC__)$/',
                ),
                6 => 
                array (
                ),
                7 => 
                array (
                ),
                8 => 
                array (
                ),
                9 => 
                array (
                ),
                10 => -1,
                11 => -1,
                12 => 
                array (
                ),
                13 => -1,
                14 => -1,
            ),
            3 => 
            array (
                0 => -1,
                1 => -1,
                2 => -1,
                3 => -1,
                4 => -1,
                5 => 
                array (
                    'reserved' => '/^(and|and_eq|asm|bitand|bitor|break|case|catch|compl|const_cast|continue|default|delete|do|dynamic_cast|else|for|fortran|friend|goto|if|new|not|not_eq|operator|or|or_eq|private|protected|public|reinterpret_cast|return|sizeof|static_cast|switch|this|throw|try|typeid|using|while|xor|xor_eq|false|true)$/',
                    'types' => '/^(auto|bool|char|class|const|double|enum|explicit|export|extern|float|inline|int|long|mutable|namespace|register|short|signed|static|struct|template|typedef|typename|union|unsigned|virtual|void|volatile|wchar_t)$/',
                    'Common Macros' => '/^(NULL|TRUE|FALSE|MAX|MIN|__LINE__|__DATA__|__FILE__|__TIME__|__STDC__)$/',
                ),
                6 => 
                array (
                ),
                7 => 
                array (
                ),
                8 => 
                array (
                ),
                9 => 
                array (
                ),
                10 => -1,
                11 => -1,
                12 => 
                array (
                ),
                13 => -1,
                14 => -1,
            ),
            4 => 
            array (
                0 => -1,
                1 => -1,
                2 => -1,
                3 => -1,
                4 => -1,
                5 => 
                array (
                    'reserved' => '/^(and|and_eq|asm|bitand|bitor|break|case|catch|compl|const_cast|continue|default|delete|do|dynamic_cast|else|for|fortran|friend|goto|if|new|not|not_eq|operator|or|or_eq|private|protected|public|reinterpret_cast|return|sizeof|static_cast|switch|this|throw|try|typeid|using|while|xor|xor_eq|false|true)$/',
                    'types' => '/^(auto|bool|char|class|const|double|enum|explicit|export|extern|float|inline|int|long|mutable|namespace|register|short|signed|static|struct|template|typedef|typename|union|unsigned|virtual|void|volatile|wchar_t)$/',
                    'Common Macros' => '/^(NULL|TRUE|FALSE|MAX|MIN|__LINE__|__DATA__|__FILE__|__TIME__|__STDC__)$/',
                ),
                6 => 
                array (
                ),
                7 => 
                array (
                ),
                8 => 
                array (
                ),
                9 => 
                array (
                ),
                10 => -1,
                11 => -1,
                12 => 
                array (
                ),
                13 => -1,
                14 => -1,
            ),
            5 => 
            array (
            ),
            6 => 
            array (
                0 => -1,
                1 => -1,
            ),
            7 => 
            array (
                0 => -1,
                1 => -1,
                2 => -1,
                3 => 
                array (
                    'reserved' => '/^(and|and_eq|asm|bitand|bitor|break|case|catch|compl|const_cast|continue|default|delete|do|dynamic_cast|else|for|fortran|friend|goto|if|new|not|not_eq|operator|or|or_eq|private|protected|public|reinterpret_cast|return|sizeof|static_cast|switch|this|throw|try|typeid|using|while|xor|xor_eq|false|true)$/',
                    'types' => '/^(auto|bool|char|class|const|double|enum|explicit|export|extern|float|inline|int|long|mutable|namespace|register|short|signed|static|struct|template|typedef|typename|union|unsigned|virtual|void|volatile|wchar_t)$/',
                    'Common Macros' => '/^(NULL|TRUE|FALSE|MAX|MIN|__LINE__|__DATA__|__FILE__|__TIME__|__STDC__)$/',
                ),
                4 => 
                array (
                ),
                5 => 
                array (
                ),
                6 => 
                array (
                ),
                7 => 
                array (
                ),
                8 => -1,
                9 => -1,
            ),
            8 => 
            array (
                0 => 
                array (
                ),
            ),
            9 => 
            array (
                0 => 
                array (
                ),
            ),
        );
        $this->_parts = array (
            0 => 
            array (
                0 => 
                array (
                    1 => 'special',
                ),
                1 => NULL,
            ),
            1 => 
            array (
                0 => 
                array (
                    1 => 'special',
                ),
                1 => NULL,
            ),
            2 => 
            array (
                0 => NULL,
                1 => NULL,
                2 => NULL,
                3 => NULL,
                4 => NULL,
                5 => NULL,
                6 => NULL,
                7 => NULL,
                8 => NULL,
                9 => NULL,
                10 => NULL,
                11 => NULL,
                12 => NULL,
                13 => NULL,
                14 => NULL,
            ),
            3 => 
            array (
                0 => NULL,
                1 => NULL,
                2 => NULL,
                3 => NULL,
                4 => NULL,
                5 => NULL,
                6 => NULL,
                7 => NULL,
                8 => NULL,
                9 => NULL,
                10 => NULL,
                11 => NULL,
                12 => NULL,
                13 => NULL,
                14 => NULL,
            ),
            4 => 
            array (
                0 => NULL,
                1 => NULL,
                2 => NULL,
                3 => NULL,
                4 => NULL,
                5 => NULL,
                6 => NULL,
                7 => NULL,
                8 => NULL,
                9 => NULL,
                10 => NULL,
                11 => NULL,
                12 => NULL,
                13 => NULL,
                14 => NULL,
            ),
            5 => 
            array (
            ),
            6 => 
            array (
                0 => NULL,
                1 => NULL,
            ),
            7 => 
            array (
                0 => NULL,
                1 => NULL,
                2 => NULL,
                3 => NULL,
                4 => NULL,
                5 => NULL,
                6 => NULL,
                7 => NULL,
                8 => NULL,
                9 => NULL,
            ),
            8 => 
            array (
                0 => NULL,
            ),
            9 => 
            array (
                0 => NULL,
            ),
        );
        $this->_subst = array (
            -1 => 
            array (
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
                5 => false,
                6 => false,
                7 => false,
                8 => false,
                9 => false,
                10 => false,
                11 => false,
                12 => false,
                13 => false,
                14 => false,
            ),
            0 => 
            array (
                0 => false,
                1 => false,
            ),
            1 => 
            array (
                0 => false,
                1 => false,
            ),
            2 => 
            array (
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
                5 => false,
                6 => false,
                7 => false,
                8 => false,
                9 => false,
                10 => false,
                11 => false,
                12 => false,
                13 => false,
                14 => false,
            ),
            3 => 
            array (
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
                5 => false,
                6 => false,
                7 => false,
                8 => false,
                9 => false,
                10 => false,
                11 => false,
                12 => false,
                13 => false,
                14 => false,
            ),
            4 => 
            array (
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
                5 => false,
                6 => false,
                7 => false,
                8 => false,
                9 => false,
                10 => false,
                11 => false,
                12 => false,
                13 => false,
                14 => false,
            ),
            5 => 
            array (
            ),
            6 => 
            array (
                0 => false,
                1 => false,
            ),
            7 => 
            array (
                0 => false,
                1 => false,
                2 => false,
                3 => false,
                4 => false,
                5 => false,
                6 => false,
                7 => false,
                8 => false,
                9 => false,
            ),
            8 => 
            array (
                0 => false,
            ),
            9 => 
            array (
                0 => false,
            ),
        );
        $this->_conditions = array (
        );
        $this->_kwmap = array (
            'reserved' => 'reserved',
            'types' => 'types',
            'Common Macros' => 'prepro',
        );
        $this->_defClass = 'code';
        $this->_checkDefines();
    }
    
}
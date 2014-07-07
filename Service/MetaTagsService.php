<?php
namespace Acilia\Bundle\MetaTagsBundle\Service;

class MetaTagsService
{
	protected $kernel;
	protected $config;

	public function __construct($kernel)
	{
		$this->kernel = $kernel;
        $this->config = [
            0 => [
                'title' => '',
                'description' => '',
                'keywords' => '',
                'og_image' => '',
                'og_image_width' => '',
                'og_image_height' => '',
                'og_type' => '',
                'og_video' => '',
                'og_video_secure_url' => '',
                'og_video_width' => '',
                'og_video_height' => '',
                'og_video_type' => ''
		    ]
        ];
	}

    public function configure($configuration)
    {
    	$order = (isset($configuration['order'])) ? $configuration['order'] : 0;

        foreach ($configuration as $key => $value) {
            if ($value != '' && $key != 'order') {
                $this->config[$order][$key] = $value;
            }
        }
    }

    protected function compileConfiguration()
    {
    	krsort($this->config);
    	$config = [];

    	foreach ($this->config as $c) {
    		foreach ($c as $key => $value) {
    			switch ($key) {
    			    case 'title':
    			    	if (isset($config['title'])) {
    			    		$config['title'] .= ' - ' . $value;
    			    	} else {
    			    		$config['title'] = $value;
    			    	}
    			    	break;

    			    case 'description':
			    	case 'keywords':
    			    	if (isset($config[$key])) {
    			    		$config[$key] .= ' ' . $value;
    			    	} else {
    			    		$config[$key] = $value;
    			    	}
    			    	break;
                    case 'order':
                        break;
                    default:
                        if (!isset($config[$key]) || trim($value)) $config[$key] = trim($value);
                        break;
    			}
    		}
    	}

    	$config['title'] = trim($config['title']);
    	$config['description'] = trim($config['description']);
    	$config['keywords'] = trim($config['keywords']);

    	return $config;
    }

    public function render()
    {
    	$config = $this->compileConfiguration();

    	$metaTags = '<!-- BEGIN META TAGS -->' . PHP_EOL;

    	$metaTags .= $this->kernel->getContainer()->get('twig')->render('AciliaMetaTagsBundle::metatags.html.twig', $config);

    	$metaTags .= $this->kernel->getContainer()->get('twig')->render('AciliaMetaTagsBundle::opengraph.html.twig', $config);

    	$metaTags .= PHP_EOL . '<!-- END META TAGS -->';
        return $metaTags;
    }
}
import os
import sys

sys.path.append(os.path.abspath('_ext'))
extensions = [
    'sphinx.ext.autosectionlabel',
    'sphinx.ext.autodoc',
    'sphinx.ext.intersphinx',
    'httpdomain',
    'tabs'
]

project = 'HelpDeskZ'
author = 'Andres Mendoza'
copyright = '2015-2021 HelpDeskZ.com'
# The short X.Y version.
version = '2.0.1'
# The full version, including alpha/beta/rc tags.
release = '2.0.1'

# -- General configuration ---------------------------------------------------

# The master toctree document.
master_doc = 'index'

# Add any Sphinx extension module names here, as strings. They can be
# extensions coming with Sphinx (named 'sphinx.ext.*') or your custom
# ones.

# Add any paths that contain templates here, relative to this directory.
# templates_path = ['_templates']

# List of patterns, relative to source directory, that match files and
# directories to ignore when looking for source files.
# This pattern also affects html_static_path and html_extra_path.
exclude_patterns = []

# The name of the Pygments (syntax highlighting) style to use.
pygments_style = 'trac'

# A dictionary of options that modify how the lexer specified by
# highlight_language generates highlighted source code.
highlight_options = {'startinline': True}

# -- Options for HTML output -------------------------------------------------

# The theme to use for HTML and HTML Help pages.  See the documentation for
# a list of builtin themes.
html_theme = 'sphinx_rtd_theme'

# Add any paths that contain custom static files (such as style sheets) here,
# relative to this directory. They are copied after the builtin static files,
# so a file named "default.css" will overwrite the builtin "default.css".
html_static_path = ['_static']

# Theme options are theme-specific and customize the look and feel of a theme
# further.  For a list of options available for each theme, see the
# documentation.
html_theme_options = {
	'collapse_navigation': False,
	'sticky_navigation': False,
	'navigation_depth': 2,
	'includehidden': False,
	'logo_only': True,
	'display_version': False
}

html_logo = 'logo.png'
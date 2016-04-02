<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class m_live_editor_c_ajax_checker extends Controller_Module
{
	public function zone_fork()
	{
		return $this->_check_disposition('disposition_id', 'url');
	}
	
	public function row_add()
	{
		return $this->_check_disposition('disposition_id');
	}
	
	public function row_move()
	{
		return $this->_check_disposition('disposition_id', 'row_id', 'position');
	}
	
	public function row_style()
	{
		return $this->_check_disposition('disposition_id', 'row_id', 'style');
	}
	
	public function row_delete()
	{
		return $this->_check_disposition('disposition_id', 'row_id');
	}
	
	public function col_add()
	{
		return $this->_check_disposition('disposition_id', 'row_id');
	}
	
	public function col_move()
	{
		return $this->_check_disposition('disposition_id', 'row_id', 'col_id', 'position');
	}
	
	public function col_size()
	{
		return $this->_check_disposition('disposition_id', 'row_id', 'col_id', 'size');
	}
	
	public function col_delete()
	{
		return $this->_check_disposition('disposition_id', 'row_id', 'col_id');
	}
	
	public function widget_add()
	{
		if ($args = list($disposition_id, $disposition, $row_id, $col_id, $title, $widget_name, $type, $settings) = $this->_check_disposition('disposition_id', 'row_id', 'col_id', 'title', 'widget', 'type', 'settings'))
		{
			$this->model()->get_widgets($widgets, $types);

			if (isset($widgets[$widget_name]) && (isset($types[$widget_name][$type]) || $type == 'index'))
			{
				return $args;
			}
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function widget_move()
	{
		return $this->_check_disposition('disposition_id', 'row_id', 'col_id', 'widget_id', 'position');
	}
	
	public function widget_style()
	{
		return $this->_check_disposition('disposition_id', 'row_id', 'col_id', 'widget_id', 'style');
	}
	
	public function widget_admin()
	{
		if ($this->user('admin'))
		{
			$post = post();
			
			if (!empty($post['widget_id']) && $widget = $this->db	->select('widget', 'type', 'settings')
																	->from('nf_widgets')
																	->where('widget_id', $post['widget_id'])
																	->row())
			{
				return array($widget['widget'], $widget['type'], $widget['settings'] ? unserialize($widget['settings']) : NULL);
			}
			else if (!empty($post['widget']) && isset($post['type']))
			{
				return array($post['widget'], $post['type'] ?: 'index');
			}
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function widget_settings()
	{
		if ((list($disposition_id, $disposition, $row_id, $col_id, $widget_id) = $this->_check_disposition('disposition_id', 'row_id', 'col_id', 'widget_id')))
		{
			if ($widget_id == -1)
			{
				return array();
			}
			else if ($widget = $this->model()->check_widget($disposition[$row_id]->cols[$col_id]->widgets[$widget_id]->widget_id))
			{
				return $widget;
			}
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function widget_update()
	{
		if ((list($disposition_id, $disposition, $row_id, $col_id, $widget_id, $title, $widget_name, $type, $settings) = $this->_check_disposition('disposition_id', 'row_id', 'col_id', 'widget_id', 'title', 'widget', 'type', 'settings')) &&
			($widget = $this->model()->check_widget($disposition[$row_id]->cols[$col_id]->widgets[$widget_id]->widget_id)))
		{
			$this->model()->get_widgets($widgets, $types);

			if (isset($widgets[$widget_name]) && (isset($types[$widget_name][$type]) || $type == 'index'))
			{
				$widget['title']    = $title;
				$widget['widget']   = $widget_name;
				$widget['type']     = $type;
				$widget['settings'] = $settings;
				
				return array_merge(array($disposition_id, $disposition, $row_id, $col_id, $widget_id), array_values($widget));
			}
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
	
	public function widget_delete()
	{
		return $this->_check_disposition('disposition_id', 'row_id', 'col_id', 'widget_id');
	}
	
	private function _check_disposition()
	{
		if ($this->user('admin') && !array_diff(array_keys($args = array_intersect_key(post(), array_flip(func_get_args()))), func_get_args()))
		{
			$args = array_merge(array_flip(func_get_args()), $args);
			array_splice($args, 1, 0, array($this->model()->get_disposition($args['disposition_id'], $theme, $page, $zone)));
			
			$args[] = $theme;
			$args[] = $page;
			$args[] = $zone;
			
			return array_values($args);
		}
		
		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/modules/live_editor/controllers/ajax_checker.php
*/
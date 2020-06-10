<div class="user-profile">
	<?php echo $user->avatar() ?>
	<h4 class="mb-3"><?php echo $user->username ?></h4>
	<?php echo $this->array
					->append_if($quote = $user->profile()->quote, '<i class="text-muted">'.$quote.'</i>')
					->append($user->profile()->first_name.' '.$user->profile()->last_name)
					->append_if($user->profile()->sex || $user->profile()->date_of_birth, function() use ($user){
						$sex = $user->profile()->sex;
						$date_of_birth = $user->profile()->date_of_birth;
						return $this->label($date_of_birth ? $this->lang('%d an|%d ans', $age = $date_of_birth->interval('today')->y, $age) : ($sex == 'female' ? 'Femme' : 'Homme'), $sex ? ($sex == 'female' ? 'fas fa-venus' : 'fas fa-mars').' '.$sex : 'fas fa-birthday-cake')
									->tooltip_if($date_of_birth, function($date){
										return $this->no_translate($date->short_date());
									});
					})
					->append_if($user->profile()->location || $user->profile()->country, function() use ($user){
						$country = $user->profile()->country;
						return $this->label($this->no_translate($user->profile()->location) ?: get_countries()[$country], $country && ($flag = image('flags/'.$country.'.png', $this->theme('default'))) ? '<img src="'.$flag.'" alt="" />' : 'fas fa-map-marker-alt');
					})
					->filter()
					->each(function($a){
						return '<h6>'.$a.'</h6>';
					}) ?>
	<?php $socials = $this	->array([
								['website',   'fas fa-globe',       ''],
								['linkedin',  'fab fa-linkedin-in', 'https://www.linkedin.com/in/'],
								['github',    'fab fa-github',      'https://github.com/'],
								['instagram', 'fab fa-instagram',   'https://www.instagram.com/'],
								['twitch',    'fab fa-twitch',      'https://www.twitch.tv/']
							])
							->filter(function($a) use ($user){
								return $user->profile()->{$a[0]};
							})
							->each(function($a) use ($user){
								return '<a href="'.$a[2].$user->profile()->{$a[0]}.'" class="btn '.$a[0].'" target="_blank">'.icon($a[1]).'</a>';
							});
	?>
	<?php if (!$socials->empty()): ?><div class="socials"><?php echo $socials ?></div><?php endif ?>
	<?php if ($this->user() && $this->user != $user) echo $this->button()->title('Contacter')->icon('far fa-envelope')->color('dark btn-block')->url('user/messages/compose/'.$user->url()) ?>
</div>

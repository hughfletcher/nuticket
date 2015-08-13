<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder {

	private $users = 200;
	private $depts = 20;
	private $staff = 10;
	private $tickets = 300;
	private $time = 500;

	protected $data = array();

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		if (!Storage::disk('local')->exists('seeds/develop')) 
		{
			$this->command->info('Dev seed data file not present. Creating...');
			$this->createData();
			
		} else {
			$this->data = unserialize(Storage::get('seeds/develop'));
			$this->command->info('Dev seed data file found.');

			if(count($this->data) < 8)
			{
				$this->command->info('Dev seed data file out of date. Creating...');
				$this->createData();
			}
			
		}

		foreach ($this->data as $class => $data) 
		{
			$full_class = 'App\\' . $class;
			$model = new $full_class;
			$table = $model->getTable();

			DB::table($table)->delete();
	        // DB::update("ALTER TABLE $table AUTO_INCREMENT = 1;");
			$model::insert($data);
			
			$this->command->getOutput()->writeln("<info>Seeded:</info> $table");
		}

	}

	protected function createData()
	{
		$this->createDeptsData();
		$this->createReportsData();
		$this->createUsersData();
		$this->createStaffData();
		$this->createTicketsData();
		$this->createTicketActionsData();
        $this->createTimeLogData();
		$this->createConfigData();

		$storage = Storage::disk('local');
		$storage->put('seeds/develop', serialize($this->data));
		$this->command->info('Dev seed data file created.');

	}

	protected function createFaker() 
	{
		$faker = Faker::create();
		$faker->seed(18772248392);
		return $faker;
	}

	protected function createDeptsData() 
	{
		$faker = $this->createFaker();

        $rl = 1;

        for ($i=1; $i <= 20; $i++) 
        { 

        	$data[$i] = [
                'id' => $i,
        		'name' => $faker->company,
        		'description' => ( $faker->boolean ? $faker->catchPhrase : null ),
        		'status' => 1,
        		'lft' => $rl++,
        		'rgt' => $rl++,
        		'updated_at' => ($date = $faker->dateTimeThisDecade()),
        		'created_at' => ( $faker->boolean ? $date : $faker->dateTimeThisDecade($date) )
        	];
        }

        $this->data['Dept'] = $data;
	}

	protected function createReportsData()
	{
		$this->data['Report'] = [
            ['name' => 'Dept Summary - Last Week', 'desc' => 'Summary of Last Week Dept Tickets', 'sql' => "SELECT qtd.id as hide_id, qtd.lft as hide_lft, qtd.rgt as hide_rgt, qtd.name as Name, (SELECT COUNT(*) FROM tickets WHERE created_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY AND dept_id = qtd.id AND deleted_at IS NULL ) AS Created, (SELECT COUNT(*) FROM depts td LEFT JOIN tickets t ON td.id = t.dept_id WHERE t.created_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY AND t.deleted_at IS NULL ) AS hide_total_created, (SELECT COUNT(*) FROM tickets WHERE closed_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY AND dept_id = qtd.id AND deleted_at IS NULL ) AS Closed, (SELECT COUNT(*) FROM depts td LEFT JOIN tickets t ON td.id = t.dept_id WHERE t.closed_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY AND t.deleted_at IS NULL ) AS hide_total_closed, (SELECT COUNT(*) FROM tickets WHERE (closed_at > CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY OR closed_at IS NULL ) AND dept_id = qtd.id AND deleted_at IS NULL ) AS 'Open/Closed', (SELECT COUNT(*) FROM tickets t LEFT JOIN depts td ON td.id = t.dept_id WHERE t.created_at < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY AND (t.closed_at > CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY OR t.closed_at IS NULL ) AND t.deleted_at IS NULL ) AS hide_total_open_new, SUM(CASE WHEN qta.created_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY AND qta.deleted_at IS NULL THEN qta.time_spent ELSE 0 END ) AS 'Time Spent', (SELECT SUM(ta.time_spent) FROM ticket_actions ta LEFT JOIN tickets t ON t.id = ta.ticket_id LEFT JOIN depts td ON td.id = t.dept_id WHERE ta.created_at BETWEEN CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY AND CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY AND ta.deleted_at IS NULL ) AS hide_total_worked_hrs FROM depts AS qtd LEFT OUTER JOIN tickets AS qt ON qtd.id = qt.dept_id LEFT JOIN ticket_actions qta ON qta.ticket_id = qt.id GROUP BY qtd.id ORDER BY qtd.lft ASC;", 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()], 
            ['name' => 'Staff Hours - WTD ', 'desc' => 'Summary of staff hours by day for the current week.', 'sql' => 'SELECT u.display_name as Name, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-1) DAY ), "%Y-%m-%d 00:00:00") AND DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-1) DAY ), "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Sun, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-2) DAY ), "%Y-%m-%d 00:00:00") AND DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-2) DAY ), "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Mon, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-3) DAY ), "%Y-%m-%d 00:00:00") AND DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-3) DAY ), "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Tue, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-4) DAY ), "%Y-%m-%d 00:00:00") AND DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-4) DAY ), "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Wed, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-5) DAY ), "%Y-%m-%d 00:00:00") AND DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-5) DAY ), "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Thur, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-6) DAY ), "%Y-%m-%d 00:00:00") AND DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-6) DAY ), "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Fri, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-7) DAY ), "%Y-%m-%d 00:00:00") AND DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-7) DAY ), "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Sat, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-1) DAY ), "%Y-%m-%d 00:00:00") AND DATE_FORMAT(DATE_SUB( CURDATE() ,INTERVAL (DAYOFWEEK(CURDATE())-7) DAY ), "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Total FROM staff s LEFT JOIN users u ON s.user_id = u.id;', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()], 
            ['name' => 'Staff Hours - Prev Week', 'desc' => 'Summary of staff hours by day for the previous week.', 'sql' => 'SELECT u.display_name as Name, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY, "%Y-%m-%d 00:00:00") AND DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY, "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Sun, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY, "%Y-%m-%d 00:00:00") AND DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+5 DAY, "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Mon, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+4 DAY, "%Y-%m-%d 00:00:00") AND DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+4 DAY, "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Tue, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+3 DAY, "%Y-%m-%d 00:00:00") AND DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+3 DAY, "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Wed, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+2 DAY, "%Y-%m-%d 00:00:00") AND DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+2 DAY, "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Thur, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+1 DAY, "%Y-%m-%d 00:00:00") AND DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+1 DAY, "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Fri, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) DAY, "%Y-%m-%d 00:00:00") AND DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) DAY, "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Sat, (SELECT COALESCE(SUM(tl.hours),0) FROM time_log AS tl WHERE time_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY, "%Y-%m-%d 00:00:00") AND DATE_FORMAT(CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) DAY, "%Y-%m-%d 23:59:59") AND tl.user_id = u.id ) AS Total FROM staff s LEFT JOIN users u ON s.user_id = u.id;', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]
        ];
	}

	protected function createUsersData() 
	{
		$faker = $this->createFaker();

		for ($i=1; $i <= 200; $i++) 
        { 

        	$data[$i] = [
                'id' => $i,
                'first_name' => ($fname = $faker->firstName),
        		'last_name' => ($lname = $faker->lastName),
                'username' => ($username = strtolower(substr($fname, 0, 1) . $lname) . $faker->randomDigit),
                'display_name' => $fname . ' ' . $lname,
                'password' => Hash::make($username),
        		'email' => $username . '@' . $faker->domainName,
        		'updated_at' => ($date = $faker->dateTimeThisDecade()),
        		'created_at' => ( $faker->boolean ? $date : $faker->dateTimeThisDecade($date) )
        	];
        }

        $this->data['User'] = $data;
	}

	protected function createStaffData() 
	{
		$faker = $this->createFaker();

		for ($i=1; $i <= 10; $i++) 
        { 

        	$data[$i] = [
                'id' => $i,
                'user_id' => ($users = $faker->unique()->numberBetween(14, 200)),
        		'department_id' => 1,
        		'updated_at' => ($date = $faker->dateTimeThisDecade()),
        		'created_at' => ( $faker->boolean ? $date : $faker->dateTimeThisDecade($date) )
        	];
        }

        $this->data['Staff'] = $data;
	}

	protected function createTicketsData() 
	{
		$faker = $this->createFaker();

		for ($i=1; $i <= 300; $i++) 
        { 

        	$data[$i] = [
                'id' => $i,
                'user_id' => $faker->numberBetween(1,200),
        		'dept_id' => $faker->numberBetween(1,20),
                'staff_id' => $staff = $faker->numberBetween(1,10),
                'status' => 'open',
                'priority' => $faker->numberBetween(1,5),
                'updated_at' => ($date = $faker->dateTimeThisDecade()),
                'created_at' => $date,
                'last_action_at' => $date,
                'hours' => 0.00
        	];

        }

        $this->data['Ticket'] = $data;
	}
	
	protected function createTicketActionsData() 
	{
		$faker = $this->createFaker();

        $i = 0;

		foreach ($this->data['Ticket'] as $id => &$row) 
		{
			//create
			$data[++$i] = [
                'id' => $i,
                'ticket_id' => $id,
                'user_id' => ($faker->boolean(75) ? ($faker->boolean() ? $this->data['Staff'][$row['staff_id']]['user_id'] : $row['user_id']) : $this->data['Staff'][$faker->numberBetween(1,10)]['user_id']),
                'type' => 'create',
                'title' => $faker->sentence($faker->numberBetween(4, 10)),
                'body' => $faker->paragraph($faker->numberBetween(1, 4)),
                'updated_at' => $date = Carbon::instance($row['created_at']),
                'created_at' => $date,
            	'assigned_id' => null,
            	'transfer_id' =>null
            ];

            //do other actions
			$action_count = $faker->numberBetween(0, 10);

			for ($a=0; $a <= $action_count; $a++) 
            { 
				$action = [
                    'id' => ++$i,
					'ticket_id' => $id, 
					'body' => $faker->paragraph($faker->numberBetween(1, 4)),
					'updated_at' => ($date = Carbon::instance($faker->dateTimeBetween($date, ($faker->boolean ? $date->copy()->addHours(8) :  $date->copy()->addDays(7))))),
                	'created_at' => $date,
                	'assigned_id' => null,
                	'transfer_id' =>null,
                	'title' => null
				];

            	if ($a == $action_count && $faker->boolean(75))
            	{
            		$action['type'] = $faker->randomElement(['closed', 'resolved']);
                } else {
                    $action['type'] = $faker->randomElement(['reply', 'reply', 'reply', 'comment', 'comment', 'assign', 'edit', 'transfer']);
                }

                //update ticket 
                $row['updated_at'] = $date;
                $row['last_action_at'] = $date;

                // dd($this->data['Staff']);

                switch ($action['type']) {
                	case 'assign':
                		$action['user_id'] = $this->data['Staff'][$row['staff_id']]['user_id'];
                		$action['assigned_id'] = $row['staff_id'] = $faker->numberBetween(1,10);
                		break;
                	
                	case 'edit':
                		$action['user_id'] = ($faker->boolean(75) ? $this->data['Staff'][$row['staff_id']]['user_id'] : $this->data['Staff'][$faker->numberBetween(1,10)]['user_id']);
                		break;
                	
                	case 'transfer':
                		$action['user_id'] = ($faker->boolean(75) ? $this->data['Staff'][$row['staff_id']]['user_id'] : $this->data['Staff'][$faker->numberBetween(1,10)]['user_id']);
                		$action['transfer_id'] = $faker->numberBetween(1,10);
                		break;
                	
                	case 'closed':
                		$action['user_id'] = ($faker->boolean(75) ? $this->data['Staff'][$row['staff_id']]['user_id'] : $this->data['Staff'][$faker->numberBetween(1,10)]['user_id']);
                		$row['status'] = 'closed';
                		break;

                	case 'resolved':
                		$action['user_id'] = ($faker->boolean(75) ? $this->data['Staff'][$row['staff_id']]['user_id'] : $this->data['Staff'][$faker->numberBetween(1,10)]['user_id']);
                		$row['status'] = 'resolved';
                		break;
                	
                	case 'reply':
                		$action['user_id'] = ($faker->boolean(75) ? ($faker->boolean() ? $this->data['Staff'][$row['staff_id']]['user_id'] : $row['user_id']) : $faker->numberBetween(1,200));
                		break;

                	case 'comment':
                		$action['user_id'] = ($faker->boolean(75) ? $this->data['Staff'][$row['staff_id']]['user_id'] : $faker->numberBetween(1,200));
                		break;
                }

                $data[$i] = $action;

			}

		}

		// dd($data[1]);

        $this->data['TicketAction'] = $data;
	}

	protected function createTimeLogData() 
	{
		$faker = $this->createFaker();

        $id = 1;

		foreach($this->data['TicketAction'] as $id => $row) 
        { 
        	if ($faker->boolean(90)) {

        		if (($row['type'] == 'reply' || $row['type'] == 'comment') && in_array($row['user_id'], array_pluck($this->data['Staff'], 'user_id'))) 
        		{
        			$data[] = [
                        'id' => $id++,
		                'user_id' => $row['user_id'],
		        		'hours' => ($hours = $faker->randomFloat(2,0,10)),
		                'type' => 'action',
		                'message' => null,
		                'ticket_action_id' => $id,
		                'updated_at' => $row['created_at'],
		                'created_at' => $row['created_at'],
		                'time_at' => $faker->dateTimeBetween($this->data['Ticket'][$row['ticket_id']]['created_at'], $row['created_at'])
		        	];

		        	$this->data['Ticket'][$row['ticket_id']]['hours'] += $hours;
        		}
        		
        	} else {
        		$data[] = [
                    'id' => $id++,
	                'user_id' => $this->data['Staff'][$faker->numberBetween(1,$this->staff)]['user_id'],
	        		'hours' => $faker->randomFloat(2,0,10),
	                'type' => $faker->randomElement(['other', 'sick', 'holiday', 'vacation']),
	                'message' => $faker->sentence,
	                'ticket_action_id' => null,
	                'updated_at' => ($date = $faker->dateTimeThisDecade),
	                'created_at' => $date,
	                'time_at' => $faker->dateTimeBetween($date)
	        	];
        	}



        }

        $this->data['TimeLog'] = $data;
	}

    protected function createConfigData() 
    {
        $this->data['Config'] = [
            ['key' => 'system.eyes', 'value' => 'blue', 'environment' => 'testing'],
            ['key' => 'system.hair', 'value' => 'brunette', 'environment' => 'testing'],
            ['key' => 'system.hottie', 'value' => true, 'environment' => 'testing']
        ];
    }

}

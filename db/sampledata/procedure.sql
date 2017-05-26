-- ----------------------------------------------------------------------------------------
# trigger before insert on Pledge, update project (curfund, pstatus) and pledge(plstatus)
# assuming that only active(e.g. processing, funded) projects will show and then be pledged
# amount over max requirement will not be accepted
drop trigger if exists updateProject;
delimiter $
use project$
create trigger updateProject before insert on Pledge
for each row
begin
  if (select count(*) from Projects where pid=new.pid 
  and curfund+new.amount >= minfund and curfund+new.amount < maxfund and pstatus ='processing') =1  then
    # processing project reaches minimum requirement, set project funded, pledge charged
    update Projects set curfund = new.amount + curfund, pstatus = 'funded' where pid = new.pid;
    set new.plstatus='charged';
  
  elseif (select count(*) from Projects where pid=new.pid 
    and curfund+new.amount < minfund and pstatus in('processing')) =1  then
    # processing project get some fund
    update Projects set curfund = new.amount + curfund where new.pid = pid;
  
  elseif (select count(*) from Projects where pid=new.pid and curfund+new.amount < maxfund and pstatus in('funded')) =1  then
    # funded project gets another fund
    update Projects set curfund = new.amount + curfund where new.pid = pid;
    set new.plstatus='charged';
  
  elseif (select count(*) from Projects where pid=new.pid 
  and curfund+new.amount = maxfund and pstatus in('processing','funded')) =1  then
    # active project reaches maximum requirement, set project closed
    update Projects set curfund = new.amount + curfund, pstatus = 'closed' where pid = new.pid;
    set new.plstatus='charged';

  else
    signal sqlstate '45000' set message_text = "ERROR INSERTION"; 
  end if;
  
end; $
delimiter ;


-- ----------------------------------------------------------------------------------------
# event detecting end of campaign, then set status of projects and pledge
drop procedure if exists detect;
delimiter $
create procedure detect()
begin
  declare done int default false;
  declare id, stat varchar(20);
  declare mifund,cfund decimal(10,2);
  declare etime datetime;
  declare project_cursor cursor for select pid, minfund, curfund, endtime, pstatus from project.Projects where pstatus in('processing','funded', 'closed');
  declare continue handler for not found set done = true;
  open project_cursor;
  project_loop: loop
    fetch project_cursor into id,mifund,cfund, etime,stat;
    if done then
	  leave project_loop;
	end if;
    if (etime >= now() and cfund >= mifund and stat in ('funded','closed')) then
      # when project's status is funded, charged all previous pending pledge
      update project.Pledge set plstatus = 'charged' where pid = id and plstatus='pending';
	end if;
    if etime < now() then # campaign ended
      if cfund >= mifund then # meet minimum requirement, then set closed
        update project.Projects set pstatus = 'closed' where pid = id;
	  else # short of money
        # pending charge cancelled, project failed
        update project.Projects set pstatus = 'failed' where pid = id;
        update project.Pledge set plstatus = 'cancelled' where pid = id;
	  end if;
	end if;
  end loop;
  close project_cursor;
end; $
delimiter ;



delimiter $
SET GLOBAL event_scheduler = 1 $
drop event if exists detectCampaign $
create event detectCampaign
on schedule every 1 minute 
COMMENT 'Saves total number of sessions then clears the table each day'
do
  call detect();$
delimiter ;
alter event detectCampaign on completion preserve enable;


#SHOW PROCESSLIST
#alter event detectCampaign on completion preserve disable;

#drop event detectCampaign;
#show variables like 'event_scheduler';
#show events;

